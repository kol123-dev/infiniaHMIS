<?php

namespace App\Repositories;

use App\Models\IpdPatientDepartment;
use App\Models\IpdPayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Stripe\Checkout\Session;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

/**
 * Class IpdPaymentRepository
 *
 * @version September 12, 2020, 11:46 am UTC
 */
class IpdPaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ipd_patient_department_id',
        'amount',
        'date',
        'note',
        'payment_mode',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return IpdPayment::class;
    }

    public function store($input)
    {
        $ipdPayment = $this->create($input);

        // update ipd bill
        $ipdPatientDepartment = IpdPatientDepartment::find($input['ipd_patient_department_id']);
        $ipdBill = $ipdPatientDepartment->bill;

        if ($ipdBill) {
            $amount = $ipdPayment->amount;
            $ipdBill->total_payments = $ipdBill->total_payments + $amount;
            $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
            $ipdBill->save();
        }

        if (isset($input['file']) && ! empty($input['file'])) {
            $ipdPayment->addMedia($input['file'])->toMediaCollection(IpdPayment::IPD_PAYMENT_PATH,
                config('app.media_disc'));
        }

        return true;
    }

    public function updateIpdPayment($input, $ipdPaymentId)
    {
        try {
            DB::beginTransaction();
            $ipdPayment = $this->update($input, $ipdPaymentId);

            if (isset($input['file']) && ! empty($input['file'])) {
                $ipdPayment->clearMediaCollection(IpdPayment::IPD_PAYMENT_PATH);
                $ipdPayment->addMedia($input['file'])->toMediaCollection(IpdPayment::IPD_PAYMENT_PATH,
                    config('app.media_disc'));
            }

            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($ipdPayment, IpdPayment::IPD_PAYMENT_PATH);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteIpdPayment($ipdPaymentId)
    {
        try {
            $ipdPayment = $this->find($ipdPaymentId);
            $ipdPayment->clearMediaCollection(IpdPayment::IPD_PAYMENT_PATH);
            $this->delete($ipdPaymentId);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function stripeSession($input)
    {
        $ipdPatientDepartment = IpdPatientDepartment::with('patient.patientUser')->find($input['ipd_patient_department_id']);

        $data = [
            'ipd_patient_department_id' => $input['ipd_patient_department_id'],
            'amount' => $input['amount'],
            'date' => $input['date'],
            'payment_mode' => $input['payment_mode'],
            'avatar_remove' => $input['avatar_remove'],
            'notes' => $input['notes'],
            'currency_symbol' => $input['currency_symbol'],
        ];

        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $ipdPatientDepartment->patient->patientUser->email,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Patient bill',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $input['amount'] : $input['amount'] * 100,
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $input['ipd_patient_department_id'],
            'mode' => 'payment',
            'success_url' => route('ipd.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => $data,
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    public function ipdStripePaymentSuccess($input)
    {
        $sessionId = $input['session_id'];

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        setStripeApiKey();

        $sessionData = Session::retrieve($sessionId);


        try {
            DB::beginTransaction();

            $ipdPayment = IpdPayment::create([
                'ipd_patient_department_id' => $sessionData->metadata->ipd_patient_department_id,
                'payment_mode' =>$sessionData->metadata->payment_mode,
                'date' =>$sessionData->metadata->date,
                'notes' =>$sessionData->metadata->notes,
                'amount' =>$sessionData->metadata->amount,
                'currency_symbol' =>$sessionData->metadata->currency_symbol,
            ]);

            // update ipd bill
            $ipdPatientDepartment = IpdPatientDepartment::find($sessionData->metadata->ipd_patient_department_id);
            $ipdBill = $ipdPatientDepartment->bill;

            if ($ipdBill) {
                $amount = $ipdPayment->amount;
                $ipdBill->total_payments = $ipdBill->total_payments + $amount;
                $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
                $ipdBill->save();
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function razorpayPayment($input)
    {
        $ipdPatientDepartment = IpdPatientDepartment::with('patient.patientUser')->find($input['ipd_patient_department_id']);

        $amount = intval(str_replace(',','',$input['amount']));

        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret_key'));
        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        $orderData = [
            'receipt' => '1',
            'amount' => $amount * 100, // 100 = 1 rupees
            'currency' => strtoupper(getCurrentCurrency()),
            'notes' => [
                'ipd_patient_department_id' => $input['ipd_patient_department_id'],
                'amount' => $amount,
                'date' => $input['date'],
                'payment_mode' => $input['payment_mode'],
                'avatar_remove' => $input['avatar_remove'],
                'notes' => $input['notes'],
                'currency_symbol' => $input['currency_symbol'],
            ],
        ];

        $razorpayOrder = $api->order->create($orderData);
        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $amount;

        return $data;
    }

    public function ipdRazorpayPaymentSuccess($input)
    {
        Log::info('RazorPay Payment Successfully');
        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret_key'));
        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        if (count($input) && ! empty($input['razorpay_payment_id'])) {
            try {
                DB::beginTransaction();

                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $generatedSignature = hash_hmac('sha256', $payment['order_id'].'|'.$input['razorpay_payment_id'],getPaymentCredentials('razorpay_secret'));

                if ($generatedSignature != $input['razorpay_signature']) {
                    if(getLoggedinPatient()){
                        return redirect(route('patient.ipd'));
                    }

                    return redirect(route('ipd.patient.index'));
                }

                // Create Transaction Here
                $ipdID = $payment['notes']['ipd_patient_department_id'];

                $ipdPayment = IpdPayment::create([
                    'ipd_patient_department_id' => $payment['notes']['ipd_patient_department_id'],
                    'payment_mode' => $payment['notes']['payment_mode'],
                    'date' => $payment['notes']['date'],
                    'notes' => $payment['notes']['notes'],
                    'amount' => $payment['notes']['amount'],
                    'currency_symbol' => $payment['notes']['currency_symbol'],
                ]);

                // update ipd bill
                $ipdPatientDepartment = IpdPatientDepartment::find($ipdID);
                $ipdBill = $ipdPatientDepartment->bill;

                if ($ipdBill) {
                    $amount = $ipdPayment->amount;
                    $ipdBill->total_payments = $ipdBill->total_payments + $amount;
                    $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
                    $ipdBill->save();
                }

                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
            return false;
        }
    }

    public function phonePePayment($input)
    {
        $amount = $input['amount'];

        $redirectbackurl = route('ipd.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('ipd.phonepe.callback'). '?' . http_build_query(['input' => $input]);

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
        $ipdID = $input['input']['ipd_patient_department_id'];

        $ipdPayment = IpdPayment::create([
            'ipd_patient_department_id' => $input['input']['ipd_patient_department_id'],
            'payment_mode' => $input['input']['payment_mode'],
            'date' => $input['input']['date'],
            'notes' => $input['input']['notes'] ?? '',
            'amount' => $input['input']['amount'],
            'currency_symbol' => $input['input']['currency_symbol'],
        ]);

        // update ipd bill
        $ipdPatientDepartment = IpdPatientDepartment::find($ipdID);
        $ipdBill = $ipdPatientDepartment->bill;

        if ($ipdBill) {
            $amount = $ipdPayment->amount;
            $ipdBill->total_payments = $ipdBill->total_payments + $amount;
            $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
            $ipdBill->save();
        }

        return true;
    }

    public function ipdPaystackPaymentSuccess($response)
    {
        try {
            DB::beginTransaction();

            $userId = getLoggedInUserId();
            $amount = $response['data']['amount'] / 100;
            $transactionID = $response['data']['id'];
            $ipdPatientId = $response['data']['metadata']['ipd_patient_id'];
            $notes = $response['data']['metadata']['notes'];
            $transactionData = [
                'transaction_id' => $transactionID,
                'amount' => $amount,
                'user_id' => $userId,
                'status' => 'paid',
                'meta' => json_encode($response),
            ];
            //Create Trasnaction
            $transaction = Transaction::create($transactionData);
            //Create ipdPayemnt
            $ipdPayment = IpdPayment::create([
                'ipd_patient_department_id' => $ipdPatientId,
                'payment_mode' => IpdPayment::PAYMENT_MODES_PAYSTACK,
                'date' => Carbon::now(),
                'notes' => $notes,
                'amount' => $amount,
                'transaction_id' => $transaction->id,
                'currency_symbol' => $response['data']['currency'],
            ]);
            // update ipd bill
            $ipdPatientDepartment = IpdPatientDepartment::find($ipdPatientId);
            $ipdBill = $ipdPatientDepartment->bill;

            if ($ipdBill) {
                $amount = $ipdPayment->amount;
                $ipdBill->total_payments = $ipdBill->total_payments + $amount;
                $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
                $ipdBill->save();
            }

            DB::commit();

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
            'amount' => $input['amount'],
            'email' => getLoggedInUser()->email,
            'tx_ref' => $reference,
            'currency' => $input['currency_symbol'],
            'redirect_url' => route('ipdFlutterwave.success'),
            'customer' => [
                'email' => getLoggedInUser()->email,
            ],
            "customizations" => [
                'title' => 'IPD Payment',
                'description' => $input['notes'],
                'logo' => asset(getLogoUrl()),
            ],
            'meta' => [
                'email' => getLoggedInUser()->email,
                'currency_symbol' => $input['currency_symbol'],
                'ipd_patient_department_id' => $input['ipd_patient_department_id'],
                'date' => $input['date'],
                'payment_mode' => $input['payment_mode'],
                'notes' => $input['notes'],
                'amount' => $input['amount'],
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            if(getLoggedinPatient()){
                return redirect(route('patient.ipd'));
            }

            return redirect(route('ipd.patient.index'));
        }

        $url = $payment['data']['link'];

        return $url;
    }

    public function ipdFlutterwavePaymentSuccess($input)
    {
        try {
            DB::beginTransaction();

            if ($input['status'] ==  'successful')
            {
                $transactionID = Flutterwave::getTransactionIDFromCallback();
                $data = Flutterwave::verifyTransaction($transactionID);

                $ipdID = $data['data']['meta']['ipd_patient_department_id'];

                $ipdPayment = IpdPayment::create([
                    'ipd_patient_department_id' => $data['data']['meta']['ipd_patient_department_id'],
                    'payment_mode' => $data['data']['meta']['payment_mode'],
                    'date' => $data['data']['meta']['date'],
                    'notes' => $data['data']['meta']['notes'] ?? '',
                    'amount' => $data['data']['meta']['amount'],
                    'currency_symbol' => $data['data']['meta']['currency_symbol']
                ]);

                // update ipd bill
                $ipdPatientDepartment = IpdPatientDepartment::find($ipdID);
                $ipdBill = $ipdPatientDepartment->bill;

                if ($ipdBill) {
                    $amount = $ipdPayment->amount;
                    $ipdBill->total_payments = $ipdBill->total_payments + $amount;
                    $ipdBill->net_payable_amount = $ipdBill->net_payable_amount - $amount;
                    $ipdBill->save();
                }

                DB::commit();
                return true;
            }


        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }
}
