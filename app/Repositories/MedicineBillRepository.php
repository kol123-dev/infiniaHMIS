<?php

namespace App\Repositories;

use App\Models\Medicine;
use App\Models\MedicineBill;
use App\Models\SaleMedicine;
use Exception;
use Flash;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Redirect;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

/**
 * Class PrescriptionRepository
 *
 * @version March 31, 2020, 12:22 pm UTC
 */
class MedicineBillRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'bill_number',
        'patient_id',
        'doctor_id',
        'model_type',
        'model_id',
        'case_id',
        'admission_id',
        'discount',
        'net_amount',
        'payment_status',
        'payment_type',
        'note',
        'tax_amount',
        'total',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return MedicineBill::class;
    }

    public function store($input)
    {
        try {
            if (isset($input['medicine'])) {
                $medicineBill = MedicineBill::create([
                    'bill_number' => 'BIL'.generateUniqueBillNumber(),
                    'patient_id' => $input['patient_id'],
                    'net_amount' => $input['net_amount'],
                    'discount' => $input['discount'],
                    'payment_status' => $input['payment_status'],
                    'payment_type' => $input['payment_type'],
                    'note' => $input['note'],
                    'total' => $input['total'],
                    'tax_amount' => $input['tax'],
                    'payment_note' => $input['payment_note'],
                    'model_type' => \App\Models\MedicineBill::class,
                ]);

                $medicineBill->update([
                    'model_id' => $medicineBill->id,
                ]);

                if ($input['category_id']) {
                    foreach ($input['category_id'] as $key => $value) {
                        $medicine = Medicine::find($input['medicine'][$key]);
                        SaleMedicine::create([
                            'medicine_bill_id' => $medicineBill->id,
                            'medicine_id' => $medicine->id,
                            'sale_price' => $input['sale_price'][$key],
                            'expiry_date' => $input['expiry_date'][$key],
                            'sale_quantity' => $input['quantity'][$key],
                            'tax' => $input['tax_medicine'][$key],
                        ]);

                        if ($input['payment_status'] == 1) {
                            $medicine->update([
                                'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
                            ]);
                        }
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($medicineBill, $input)
    {
        try {
            DB::beginTransaction();
            $input['payment_status'] = isset($input['payment_status']) ? 1 : $medicineBill->payment_status;

            foreach ($input['medicine'] as $key => $inputSale) {
                if (empty($input['medicine'][$key]) && $input['payment_status'] == false) {
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.medicine_not_selected'));
                }
                $saleMedincine = SaleMedicine::where('medicine_bill_id', $input['medicine_bill'])->where('medicine_id', $input['medicine'][$key])->first();

                if (isset($saleMedincine->sale_quantity) && $input['quantity'][$key]) {
                    if ($saleMedincine->sale_quantity < $input['quantity'][$key] && $input['payment_status'] == 1) {
                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.update_quantity'));
                    }
                }
            }

            $medicineBill->load('saleMedicine');
            $previousMedicineIds = $medicineBill->saleMedicine->pluck('medicine_id');
            $previousMedicineArray = [];

            foreach ($previousMedicineIds as $previousMedicineId) {
                $previousMedicineArray[] = $previousMedicineId;
            }

            $deleteIds = array_diff($previousMedicineArray, $input['medicine']);
            if ($input['payment_status'] && $medicineBill->payment_status == true) {
                foreach ($deleteIds as $key => $value) {
                    if (array_key_exists($key, $input['medicine'])) {
                        $updatedMedicine = Medicine::find($input['medicine'][$key]);
                        if ($updatedMedicine->available_quantity < $input['quantity'][$key]) {
                            $available = $updatedMedicine->available_quantity == null ? 0 : $updatedMedicine->available_quantity;

                            throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$updatedMedicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                        }
                    }

                }
                foreach ($deleteIds as $deleteId) {
                    $deleteMedicine = Medicine::find($deleteId);
                    $saleMedicine = SaleMedicine::where('medicine_bill_id', $medicineBill->id)->where('medicine_id', $deleteId)->first();
                    $deleteMedicine->update(['available_quantity' => $deleteMedicine->available_quantity + $saleMedicine->sale_quantity]);
                }
                foreach ($deleteIds as $key => $value) {
                    $updatedMedicine = Medicine::find($input['medicine'][$key]);
                    $updatedMedicine->update([
                        'available_quantity' => $updatedMedicine->available_quantity - $input['quantity'][$key],
                    ]);
                }
            }
            $arr = collect($input['medicine']);
            $duplicateIds = $arr->duplicates();
            $prescriptionMedicineArray = [];
            $inputdoseAndMedicine = [];

            foreach ($medicineBill->saleMedicine as $saleMedicine) {
                $prescriptionMedicineArray[$saleMedicine->medicine_id] = $saleMedicine->sale_quantity;
            }

            foreach ($input['medicine'] as $key => $value) {
                $inputdoseAndMedicine[$value] = $input['quantity'][$key];
            }

            foreach ($input['medicine'] as $key => $value) {
                $result = array_intersect($prescriptionMedicineArray, $inputdoseAndMedicine);

                $medicine = Medicine::find($input['medicine'][$key]);
                if (! empty($duplicateIds)) {
                    foreach ($duplicateIds as $key => $value) {
                        $medicine = Medicine::find($duplicateIds[$key]);

                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.duplicate_medicine'));
                    }
                }
                $saleMedicine = SaleMedicine::where('medicine_bill_id', $medicineBill->id)->where('medicine_id', $medicine->id)->first();
                $qty = $input['quantity'][$key];

                if ($input['payment_status'] == true && $medicine->available_quantity < $qty && $medicineBill->payment_status == 0) {
                    $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                }

                if (! is_null($saleMedicine) && $input['payment_status'] == 1 && $medicineBill['payment_status'] == 1) {
                    $PreviousQty = $saleMedicine->sale_quantity == null ? 0 : $saleMedicine->sale_quantity;
                    if ($PreviousQty > $qty) {
                        $medicine->update([
                            'available_quantity' => $medicine->available_quantity + $PreviousQty - $qty,
                        ]);
                    }
                }

                if (! array_key_exists($input['medicine'][$key], $result) && $medicine->available_quantity < $qty && $input['payment_status'] == false) {
                    $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                }

            }
            $medicineBill->saleMedicine()->delete();

            $beforeStatus = $medicineBill['payment_status'];
            $medicineBill->Update([
                'patient_id' => $input['patient_id'],
                'net_amount' => $input['net_amount'],
                'discount' => $input['discount'],
                'payment_status' => $input['payment_status'],
                'payment_type' => $input['payment_type'],
                'total' => $input['total'],
                'tax_amount' => $input['tax'],
                'note' => $input['note'],
                'bill_date' => $input['bill_date'],
            ]);

            if ($input['category_id']) {
                foreach ($input['category_id'] as $key => $value) {
                    $medicine = Medicine::find($input['medicine'][$key]);

                    $saleMedicine = new SaleMedicine();
                    $saleMedicine->medicine_bill_id = $medicineBill->id;
                    $saleMedicine->medicine_id = $medicine->id;
                    $saleMedicine->sale_price = $input['sale_price'][$key];
                    $saleMedicine->expiry_date = $input['expiry_date'][$key];
                    $saleMedicine->sale_quantity = $input['quantity'][$key];
                    $saleMedicine->tax = $input['tax_medicine'][$key] == null ? 0 : $input['tax_medicine'][$key];
                    $saleMedicine->save();

                    if ($input['payment_status'] == 1 && $beforeStatus == 0
                    ) {
                        $medicine->update([
                            'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
                        ]);
                    }
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function medicineBillStore($input)
    {
        try {

            DB::beginTransaction();

            if (empty($input['medicine'])) {
                throw new UnprocessableEntityHttpException(__('messages.medicine_bills.medicine_not_selected'));
            }

            $arr = collect($input['medicine']);
            $duplicateIds = $arr->duplicates();

            $input['payment_status'] = isset($input['payment_status']) ? 1 : 0;

            foreach ($input['medicine'] as $key => $value) {
                $medicine = Medicine::find($input['medicine'][$key]);
                if (! empty($duplicateIds)) {
                    foreach ($duplicateIds as $key => $value) {
                        $medicine = Medicine::find($duplicateIds[$key]);
                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.duplicate_medicine'));
                    }
                }
                $qty = $input['quantity'][$key];

                if ($medicine->available_quantity < $qty) {
                    $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                }
            }

            $medicineBill = MedicineBill::create([
                'bill_number' => 'BIL'.generateUniqueBillNumber(),
                'patient_id' => $input['patient_id'],
                'net_amount' => $input['net_amount'],
                'discount' => $input['discount'],
                'payment_status' => $input['payment_status'],
                'payment_type' => $input['payment_type'],
                'note' => $input['note'],
                'total' => $input['total'],
                'tax_amount' => $input['tax'],
                'payment_note' => $input['payment_note'],
                'model_type' => \App\Models\MedicineBill::class,
                'bill_date' => $input['bill_date'],
            ]);

            $medicineBill->update([
                'model_id' => $medicineBill->id,
            ]);

            if ($input['category_id']) {
                foreach ($input['category_id'] as $key => $value) {
                    $medicine = Medicine::find($input['medicine'][$key]);
                    $tax = $input['tax_medicine'][$key] == null ? $input['tax_medicine'][$key] : 0;

                    $saleMedicine = new SaleMedicine();
                    $saleMedicine->medicine_bill_id = $medicineBill->id;
                    $saleMedicine->medicine_id = $medicine->id;
                    $saleMedicine->sale_price = $input['sale_price'][$key];
                    $saleMedicine->expiry_date = $input['expiry_date'][$key];
                    $saleMedicine->sale_quantity = $input['quantity'][$key];
                    $saleMedicine->tax = $tax;
                    $saleMedicine->save();

                    if ($input['payment_status'] == 1) {
                        $medicine->update([
                            'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
                        ]);
                    }
                }
                DB::commit();

                return $medicineBill;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function stripeSession($input, $medicineBill)
    {
        $input['bill_number'] = $medicineBill->bill_number;

        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Medicine bill',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $input['net_amount'] : ($input['net_amount'] * 100),
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $input['patient_id'],
            'mode' => 'payment',
            'success_url' => route('medicine.bill.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('medicine.bill.stripe.failed').'?'.http_build_query(['input' => $input]),
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    public function medicineBillstripeSuccess($input)
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

    public function medicineBillstripeFailed($input)
    {
        $input = $input['input'];

        try {
            $input['payment_status'] = isset($input['payment_status']) ? 1 : 0;

                $medicineBill = MedicineBill::where('bill_number',$input['bill_number'])->first();

                if ($input['category_id']) {
                    foreach ($input['category_id'] as $key => $value) {
                        $medicine = Medicine::find($input['medicine'][$key]);
                        $tax = $input['tax_medicine'][$key] == null ? $input['tax_medicine'][$key] : 0;

                        $saleMedicine = SaleMedicine::where('medicine_bill_id',$medicineBill->id)->first();
                        $saleMedicine->delete();

                        if ($input['payment_status'] == 1) {
                            $medicine->update([
                                'available_quantity' => $input['quantity'][$key] + $medicine->available_quantity,
                            ]);
                        }
                    }
                }

                $medicineBill->delete();

                return true;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
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
        $data['net_amount'] = $amount;

        return $data;
    }

    public function razorPayPaymentSuccess($input)
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

    public function phonePePayment($input)
    {
        $amount = $input['net_amount'];

        $redirectbackurl = route('medicine.bill.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('medicine.bill.phonepe.callback'). '?' . http_build_query(['input' => $input]);

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

            if (empty($input['medicine'])) {
                throw new UnprocessableEntityHttpException(__('messages.medicine_bills.medicine_not_selected'));
            }

            $arr = collect($input['medicine']);
            $duplicateIds = $arr->duplicates();

            $input['payment_status'] = isset($input['payment_status']) ? 1 : 0;

            foreach ($input['medicine'] as $key => $value) {
                $medicine = Medicine::find($input['medicine'][$key]);
                if (! empty($duplicateIds)) {
                    foreach ($duplicateIds as $key => $value) {
                        $medicine = Medicine::find($duplicateIds[$key]);
                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.duplicate_medicine'));
                    }
                }
                $qty = $input['quantity'][$key];

                if ($medicine->available_quantity < $qty) {
                    $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                }
            }

            $medicineBill = MedicineBill::create([
                'bill_number' => 'BIL'.generateUniqueBillNumber(),
                'patient_id' => $input['patient_id'],
                'net_amount' => $input['net_amount'],
                'discount' => $input['discount'],
                'payment_status' => $input['payment_status'],
                'payment_type' => $input['payment_type'],
                'note' => $input['note'] ?? null,
                'total' => $input['total'],
                'tax_amount' => $input['tax'],
                'payment_note' => $input['payment_note'] ?? null,
                'model_type' => \App\Models\MedicineBill::class,
                'bill_date' => $input['bill_date'],
            ]);

            $medicineBill->update([
                'model_id' => $medicineBill->id,
            ]);

            if ($input['category_id']) {
                foreach ($input['category_id'] as $key => $value) {
                    $medicine = Medicine::find($input['medicine'][$key]);
                    $tax = $input['tax_medicine'][$key] == null ? $input['tax_medicine'][$key] : 0;

                    SaleMedicine::create([
                        'medicine_bill_id' => $medicineBill->id,
                        'medicine_id' => $medicine->id,
                        'sale_price' => $input['sale_price'][$key],
                        'expiry_date' => $input['expiry_date'][$key] ?? null,
                        'sale_quantity' => $input['quantity'][$key],
                        'tax' => $tax,
                    ]);

                    if ($input['payment_status'] == 1) {
                        $medicine->update([
                            'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
                        ]);
                    }
                }
                DB::commit();

                return true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }

    public function paystackPaymentSuccess($response)
    {
        $input = $response['data']['metadata']['data'];

        try {
            DB::beginTransaction();

            if (empty($input['medicine'])) {
                throw new UnprocessableEntityHttpException(__('messages.medicine_bills.medicine_not_selected'));
            }

            $arr = collect($input['medicine']);
            $duplicateIds = $arr->duplicates();

            $input['payment_status'] = isset($input['payment_status']) ? 1 : 0;

            foreach ($input['medicine'] as $key => $value) {
                $medicine = Medicine::find($input['medicine'][$key]);
                if (! empty($duplicateIds)) {
                    foreach ($duplicateIds as $key => $value) {
                        $medicine = Medicine::find($duplicateIds[$key]);
                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.duplicate_medicine'));
                    }
                }
                $qty = $input['quantity'][$key];

                if ($medicine->available_quantity < $qty) {
                    $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                    throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                }
            }

            $medicineBill = MedicineBill::create([
                'bill_number' => 'BIL'.generateUniqueBillNumber(),
                'patient_id' => $input['patient_id'],
                'net_amount' => $input['net_amount'],
                'discount' => $input['discount'],
                'payment_status' => $input['payment_status'],
                'payment_type' => $input['payment_type'],
                'note' => $input['note'] ?? null,
                'total' => $input['total'],
                'tax_amount' => $input['tax'],
                'payment_note' => $input['payment_note'] ?? null,
                'model_type' => \App\Models\MedicineBill::class,
                'bill_date' => $input['bill_date'],
            ]);

            $medicineBill->update([
                'model_id' => $medicineBill->id,
            ]);

            if ($input['category_id']) {
                foreach ($input['category_id'] as $key => $value) {
                    $medicine = Medicine::find($input['medicine'][$key]);
                    $tax = $input['tax_medicine'][$key] == null ? $input['tax_medicine'][$key] : 0;

                    SaleMedicine::create([
                        'medicine_bill_id' => $medicineBill->id,
                        'medicine_id' => $medicine->id,
                        'sale_price' => $input['sale_price'][$key],
                        'expiry_date' => $input['expiry_date'][$key] ?? null,
                        'sale_quantity' => $input['quantity'][$key],
                        'tax' => $tax,
                    ]);

                    if ($input['payment_status'] == 1) {
                        $medicine->update([
                            'available_quantity' => $medicine->available_quantity - $input['quantity'][$key],
                        ]);
                    }
                }
                DB::commit();

                return true;
            }
        }catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
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
            'redirect_url' => route('medicine.bill.flutterwave.success'),
            'customer' => [
                'email' => getLoggedInUser()->email,
            ],
            "customizations" => [
                'title' => 'Medicine Bill payment',
                'description' => $input['payment_note'],
                'logo' => asset(getLogoUrl()),
            ],
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            return redirect(route('medicine-bills.index'));
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

                $sessionData = session()->get('flutterwaveMedicineBillData');

                if(isset($sessionData) && !empty($sessionData)){

                    if (empty($sessionData['medicine'])) {
                        throw new UnprocessableEntityHttpException(__('messages.medicine_bills.medicine_not_selected'));
                    }

                    $arr = collect($sessionData['medicine']);
                    $duplicateIds = $arr->duplicates();

                    $sessionData['payment_status'] = isset($sessionData['payment_status']) ? 1 : 0;

                    foreach ($sessionData['medicine'] as $key => $value) {
                        $medicine = Medicine::find($sessionData['medicine'][$key]);
                        if (! empty($duplicateIds)) {
                            foreach ($duplicateIds as $key => $value) {
                                $medicine = Medicine::find($duplicateIds[$key]);
                                throw new UnprocessableEntityHttpException(__('messages.medicine_bills.duplicate_medicine'));
                            }
                        }
                        $qty = $sessionData['quantity'][$key];

                        if ($medicine->available_quantity < $qty) {
                            $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;
                            throw new UnprocessableEntityHttpException(__('messages.medicine_bills.available_quantity').' '.$medicine->name.' '.__('messages.medicine_bills.is').' '.$available.'.');
                        }
                    }

                    $medicineBill = MedicineBill::create([
                        'bill_number' => 'BIL'.generateUniqueBillNumber(),
                        'patient_id' => $sessionData['patient_id'],
                        'net_amount' => $sessionData['net_amount'],
                        'discount' => $sessionData['discount'],
                        'payment_status' => $sessionData['payment_status'],
                        'payment_type' => $sessionData['payment_type'],
                        'note' => $sessionData['note'] ?? null,
                        'total' => $sessionData['total'],
                        'tax_amount' => $sessionData['tax'],
                        'payment_note' => $sessionData['payment_note'] ?? null,
                        'model_type' => \App\Models\MedicineBill::class,
                        'bill_date' => $sessionData['bill_date'],
                    ]);

                    $medicineBill->update([
                        'model_id' => $medicineBill->id,
                    ]);

                    if ($sessionData['category_id']) {
                        foreach ($sessionData['category_id'] as $key => $value) {
                            $medicine = Medicine::find($sessionData['medicine'][$key]);
                            $tax = $sessionData['tax_medicine'][$key] == null ? $sessionData['tax_medicine'][$key] : 0;

                            SaleMedicine::create([
                                'medicine_bill_id' => $medicineBill->id,
                                'medicine_id' => $medicine->id,
                                'sale_price' => $sessionData['sale_price'][$key],
                                'expiry_date' => $sessionData['expiry_date'][$key] ?? null,
                                'sale_quantity' => $sessionData['quantity'][$key],
                                'tax' => $tax,
                            ]);

                            if ($sessionData['payment_status'] == 1) {
                                $medicine->update([
                                    'available_quantity' => $medicine->available_quantity - $sessionData['quantity'][$key],
                                ]);
                            }
                        }
                        DB::commit();
                        session()->forget('flutterwaveMedicineBillData');

                        return true;
                    }
                }
            }
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }
}
