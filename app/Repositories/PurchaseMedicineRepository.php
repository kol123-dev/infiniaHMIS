<?php

namespace App\Repositories;

use App\Models\Accountant;
use App\Models\Address;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\PurchasedMedicine;
use App\Models\PurchaseMedicine;
use App\Models\User;
use Arr;
use Exception;
use Flash;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

/**
 * Class AccountantRepository
 *
 * @version February 17, 2020, 5:34 am UTC
 */
class PurchaseMedicineRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'purchase_numeber',
        'purchase_date',
        'bill_number',
        'supplier_name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PurchaseMedicine::class;
    }

    public function getMedicine()
    {
        $data['medicines'] = Medicine::all()->pluck('name', 'id')->toArray();

        return $data;
    }

    public function getMedicineList()
    {
        $result = Medicine::all()->pluck('name', 'id')->toArray();

        $medicines = [];
        foreach ($result as $key => $item) {
            $medicines[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $medicines;
    }

    public function getCategoryList()
    {
        $result = Category::all()->pluck('name', 'id')->toArray();

        $category = [];
        foreach ($result as $key => $item) {
            $medicines[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $category;
    }

    public function getCategory()
    {
        $data['categories'] = Category::all()->pluck('name', 'id')->toArray();

        return $data;

    }

    public function store($input)
    {
        try {
            DB::beginTransaction();
            $purchaseMedicineArray = Arr::only($input, $this->model->getFillable());
            $purchaseMedicine = PurchaseMedicine::create($purchaseMedicineArray);

            foreach ($input['medicine'] as $key => $value) {

                $purchasedMedicineArray = [
                    'purchase_medicines_id' => $purchaseMedicine->id,
                    'medicine_id' => $input['medicine'][$key],
                    'lot_no' => $input['lot_no'][$key],
                    'tax' => $input['tax_medicine'][$key],
                    'expiry_date' => $input['expiry_date'][$key],
                    'quantity' => $input['quantity'][$key],
                    'amount' => $input['amount'][$key],
                    'tenant_id',
                ];

                PurchasedMedicine::create($purchasedMedicineArray);
                $medicine = Medicine::find($input['medicine'][$key]);
                $medicineQtyArray = [
                    'quantity' => $input['quantity'][$key] + $medicine->quantity,
                    'available_quantity' => $input['quantity'][$key] + $medicine->available_quantity,
                ];
                $medicine->update($medicineQtyArray);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($accountant, $input)
    {
        try {
            unset($input['password']);

            $user = User::find($accountant->user->id);

            if (isset($input['image']) && ! empty($input['image'])) {
                $mediaId = updateProfileImage($user, $input['image']);
            }

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($user, User::COLLECTION_PROFILE_PICTURES);
            }

            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $accountant->user->update($input);
            $accountant->update($input);

            if (! empty($accountant->address)) {
                if (empty($address = Address::prepareAddressArray($input))) {
                    $accountant->address->delete();
                }
                $accountant->address->update($input);
            } else {
                if (! empty($address = Address::prepareAddressArray($input)) && empty($accountant->address)) {
                    $ownerId = $accountant->id;
                    $ownerType = Accountant::class;
                    Address::create(array_merge($address, ['owner_id' => $ownerId, 'owner_type' => $ownerType]));
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function stripeSession($input)
    {
        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Purchase Medicine',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $input['net_amount'] : $input['net_amount'] * 100,
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $input['purchase_no'],
            'mode' => 'payment',
            'success_url' => route('medicine.purchase.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('medicine.purchase.stripe.failed').'?'.http_build_query(['input' => $input]),
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    public function purchaseMedicinestripeSuccess($input)
    {
        $sessionId = $input['session_id'];

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        setStripeApiKey();

        $sessionData = Session::retrieve($sessionId);

        if($sessionData){
            return true;
        }

        return false;
    }

    public function razorPayPayment($input)
    {
        $amount = $input['net_amount'];

        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        $orderData = [
            'receipt' => '1',
            'amount' => $amount * 100,
            'currency' => strtoupper(getCurrentCurrency()),
            'notes' => [
                'amount' => $amount,
            ],
        ];

        $razorpayOrder = $api->order->create($orderData);
        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $amount;

        return $data;
    }

    public function razorPaySuccess($input)
    {
        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        if (count($input) && ! empty($input['razorpay_payment_id'])) {

            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            $generatedSignature = hash_hmac('sha256', $payment['order_id'].'|'.$input['razorpay_payment_id'],getPaymentCredentials('razorpay_secret'));

            if ($generatedSignature != $input['razorpay_signature']) {

                Flash::error(__('messages.payment.payment_failed'));

                return redirect(route('medicine-purchase.index'));
            }

            return true;
        }
    }

    public function paystackPaymentSuccess($response)
    {
        $input = $response['data']['metadata']['data'];

        try {
            DB::beginTransaction();

            $purchaseMedicineArray = Arr::only($input, $this->model->getFillable());
            $purchaseMedicine = PurchaseMedicine::create($purchaseMedicineArray);

            foreach ($input['medicine'] as $key => $value) {

                $purchasedMedicineArray = [
                    'purchase_medicines_id' => $purchaseMedicine->id,
                    'medicine_id' => $input['medicine'][$key],
                    'lot_no' => $input['lot_no'][$key],
                    'tax' => $input['tax_medicine'][$key],
                    'expiry_date' => $input['expiry_date'][$key] ?? null,
                    'quantity' => $input['quantity'][$key],
                    'amount' => $input['amount'][$key],
                    'tenant_id',
                ];

                PurchasedMedicine::create($purchasedMedicineArray);
                $medicine = Medicine::find($input['medicine'][$key]);
                $medicineQtyArray = [
                    'quantity' => $input['quantity'][$key] + $medicine->quantity,
                    'available_quantity' => $input['quantity'][$key] + $medicine->available_quantity,
                ];
                $medicine->update($medicineQtyArray);
            }

            DB::commit();

            return true;

        }catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function phonePePayment($input)
    {
        $amount = $input['net_amount'];

        $redirectbackurl = route('purchase.medicine.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('purchase.medicine.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        config([
            'phonepe.merchantId' => $merchantId,
            'phonepe.merchantUserId' => $merchantUserId,
            'phonepe.env' => $baseUrl,
            'phonepe.saltKey' => $saltKey,
            'phonepe.saltIndex' => $saltIndex,
            'phonepe.redirectUrl' => $redirectbackurl,
            'phonepe.callBackUrl' => $callbackurl,
        ]);

        $data = array(
            'merchantId' => $merchantId,
            'merchantTransactionId' => $merchantTransactionId,
            'merchantUserId' => $merchantUserId,
            'amount' => $amount * 100,
            'redirectUrl' => $redirectbackurl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackurl,
            'paymentInstrument' =>
                array(
                    'type' => 'PAY_PAGE'
                ),
        );

        $encode = base64_encode(json_encode($data));

        $string = $encode . '/pg/v1/pay' . $saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl . '/pg/v1/pay',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['request' => $encode]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-VERIFY: ' . $finalXHeader
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $rData = json_decode($response);
        $url = $rData->data->instrumentResponse->redirectInfo->url;

        return $url;
    }

    public function phonePePaymentSuccess($input)
    {
        $input = $input['input'];

        try{
            DB::beginTransaction();

            $purchaseMedicineArray = Arr::only($input, $this->model->getFillable());
            $purchaseMedicine = PurchaseMedicine::create($purchaseMedicineArray);

            foreach ($input['medicine'] as $key => $value) {

                $purchasedMedicineArray = [
                    'purchase_medicines_id' => $purchaseMedicine->id,
                    'medicine_id' => $input['medicine'][$key],
                    'lot_no' => $input['lot_no'][$key],
                    'tax' => $input['tax_medicine'][$key],
                    'expiry_date' => $input['expiry_date'][$key],
                    'quantity' => $input['quantity'][$key],
                    'amount' => $input['amount'][$key],
                ];

                PurchasedMedicine::create($purchasedMedicineArray);
                $medicine = Medicine::find($input['medicine'][$key]);
                $medicineQtyArray = [
                    'quantity' => $input['quantity'][$key] + $medicine->quantity,
                    'available_quantity' => $input['quantity'][$key] + $medicine->available_quantity,
                ];
                $medicine->update($medicineQtyArray);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }

    public function flutterWavePayment($input)
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

         // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $input['net_amount'],
            'email' => getLoggedInUser()->email,
            'tx_ref' => $reference,
            'currency' => getCurrentCurrency(),
            'redirect_url' => route('purchase.medicine.flutterwave.success'),
            'customer' => [
                'email' => getLoggedInUser()->email,
            ],
            "customizations" => [
                'title' => 'Purchase Medicine Payment',
                'description' => $input['payment_note'],
                'logo' => asset(getLogoUrl()),
            ],
        ];

        $payment = FlutterWave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            return redirect()->route('medicine-purchase.index');
        }

        $url = $payment['data']['link'];

        return $url;
    }

    public function flutterwavePaymentSuccess($input)
    {
        try {
            DB::beginTransaction();

            if ($input['status'] ==  'successful')
            {
                $transactionID = Flutterwave::getTransactionIDFromCallback();
                $data = Flutterwave::verifyTransaction($transactionID);

                $sessionData = session()->get('fluttterWaveInput');

                if(isset($sessionData) && !empty($sessionData)){
                    $purchaseMedicineArray = Arr::only($sessionData, $this->model->getFillable());
                    $purchaseMedicine = PurchaseMedicine::create($purchaseMedicineArray);

                    foreach ($sessionData['medicine'] as $key => $value) {

                        $purchasedMedicineArray = [
                            'purchase_medicines_id' => $purchaseMedicine->id,
                            'medicine_id' => $sessionData['medicine'][$key],
                            'lot_no' => $sessionData['lot_no'][$key],
                            'tax' => $sessionData['tax_medicine'][$key],
                            'expiry_date' => $sessionData['expiry_date'][$key],
                            'quantity' => $sessionData['quantity'][$key],
                            'amount' => $sessionData['amount'][$key],
                        ];

                        PurchasedMedicine::create($purchasedMedicineArray);
                        $medicine = Medicine::find($sessionData['medicine'][$key]);
                        $medicineQtyArray = [
                            'quantity' => $sessionData['quantity'][$key] + $medicine->quantity,
                            'available_quantity' => $sessionData['quantity'][$key] + $medicine->available_quantity,
                        ];
                        $medicine->update($medicineQtyArray);
                    }
                }

                DB::commit();
                session()->forget('fluttterWaveInput');
                return true;
            }
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }
}
