<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\AppointmentTransaction;
use App\Repositories\BaseRepository;
use Arr;
use Exception;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class AppointmentTransactionRepository extends BaseRepository
{
    protected $fieldSearchable = [

    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AppointmentTransaction::class;
    }

    public function store($input){
        try {

            $appointment = Appointment::find($input['id']);
            $appointment->update(['payment_status' => 1]);

            return true;

        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    public function WebAppointmentstripeSession($input)
    {
        $appointment = Appointment::find($input->id);
        $data = [
            'appointment_id' => $input->id,
            'amount' => $input->appointment_charge,
            'payment_mode' => $input->payment_type,
        ];

        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $appointment->patient->patientUser->email,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Patient bill',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $input['appointment_charge'] : $input['appointment_charge'] * 100,
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $input->id,
            'mode' => 'payment',
            'success_url' => route('web.appointment.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => $data,
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    public function stripeSession($input)
    {
        $appointment = Appointment::find($input->id);
        $data = [
            'appointment_id' => $input->id,
            'amount' => $input->appointment_charge,
            'payment_mode' => $input->payment_type,
        ];

        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $appointment->patient->patientUser->email,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Patient bill',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $input['appointment_charge'] : $input['appointment_charge'] * 100,
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $input->id,
            'mode' => 'payment',
            'success_url' => route('appointment.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('appointment.stripe.failure', ['appointment_id' =>  $input->id]),
            'metadata' => $data,
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    public function appointmentStripePaymentSuccess($input)
    {
        $sessionId = $input['session_id'];
        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        setStripeApiKey();

        $sessionData = Session::retrieve($sessionId);


        try {
            DB::beginTransaction();

            $appointmentTransaction = AppointmentTransaction::create([
                'transaction_id' => $sessionData->id,
                'appointment_id' => $sessionData->metadata->appointment_id,
                'payment_type' =>$sessionData->metadata->payment_mode,
                'amount' =>$sessionData->metadata->amount,
            ]);

            // update appoitment payment Status
            $appointment = Appointment::find($sessionData->metadata->appointment_id);

            $appointment->update(['is_completed' => false,'payment_status' => 1,'payment_type' => \App\Models\Appointment::TYPE_STRIPE]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function TransactionRazorpayPayment($input)
    {
        // $amount = $input['appointment_charge'];
        $amount = intval(str_replace(',','',$input['appointment_charge']));

        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret_key'));
        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        $orderData = [
            'receipt' => '1',
            'amount' => $amount * 100, // 100 = 1 rupees
            'currency' => strtoupper(getCurrentCurrency()),
            'notes' => [
                'appointment_id' => $input['appointment_id'],
                'amount' => $amount,
                'payment_mode' => $input['payment_mode'],
            ],
        ];

        $razorpayOrder = $api->order->create($orderData);

        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $amount;
        $data['payment_mode'] = $input['payment_mode'];
        $data['appointment_id'] = $input['appointment_id'];

        return $data;
    }

    public function TransactionRazorpayPaymentSuccess($input)
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
                    return redirect()->back();
                }

                // Create Transaction Here
                $appointmentTransaction = AppointmentTransaction::create([
                    'transaction_id' => $payment['id'],
                    'appointment_id' => $payment['notes']['appointment_id'],
                    'payment_type' => $payment['notes']['payment_mode'],
                    'amount' => $payment['notes']['amount'],
                ]);

                // update appoitment payment Status
                $appointment = Appointment::find($payment['notes']['appointment_id']);
                $appointment->update(['is_completed' => false,'payment_status' => 1,'payment_type' => \App\Models\Appointment::TYPE_RAZORPAY]);

                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
            return false;
        }
    }
    public function paypalPaymentSuccess($response)
    {

        try {
            DB::beginTransaction();

            $transactionID = $response['purchase_units'][0]['payments']['captures'][0]['id'];
            $appointmentId = $response['purchase_units'][0]['reference_id'];
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            $transactionData = [
                'transaction_id' => $transactionID,
                'appointment_id' => $appointmentId,
                'payment_type' => 5,
                'amount' => $amount
            ];

            $transaction = AppointmentTransaction::create($transactionData);

            $appointment = Appointment::find($appointmentId);
            $appointment->update(['is_completed' => false,'payment_status' => 1,'payment_type' => \App\Models\Appointment::TYPE_PAYPAL]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    public function flutterWavePayment($input)
    {
        $reference = Flutterwave::generateReference();
        session(['appointmentflutterwaveData' => $input]);

        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $input['appointment_charge'],
            'email' => getLoggedInUser()->email,
            'tx_ref' => $reference,
            'currency' => getCurrentCurrency(),
            'redirect_url' => route('appointment.flutterwave.success'). '?' . http_build_query(['input' => Arr::only($input,'web_appointment')]),
            'customer' => [
                'email' => getLoggedInUser()->email,
            ],
            "customizations" => [
                'title' => 'Appointment booking Payment',
                'logo' => asset(getLogoUrl()),
            ],
        ];

        $payment = FlutterWave::initializePayment($data);

        if ($payment['status'] !== 'success') {

            if(isset($input['web_appointment']) && $input['web_appointment'] == true){

                Flash::error(__('messages.payment.payment_failed'));

                return redirect()->route('appointment');
            }

            Flash::error(__('messages.payment.payment_failed'));

            return redirect()->route('appointments.index');
        }

        $url = $payment['data']['link'];

        return $url;
    }

    public function flutterwavePaymentSuccess($input)
    {
        try {
            DB::beginTransaction();

            $sessionData = session()->get('appointmentflutterwaveData');

            if ($input['status'] ==  'successful')
            {
                $transactionID = Flutterwave::getTransactionIDFromCallback();
                $data = Flutterwave::verifyTransaction($transactionID);

                $appointment = Appointment::create($sessionData);

                $appointmentTransaction = AppointmentTransaction::create([
                    'transaction_id' => $input['transaction_id'],
                    'appointment_id' => $appointment->id,
                    'payment_type' => $sessionData['payment_mode'],
                    'amount' => $sessionData['appointment_charge'],
                ]);

                // update appoitment payment Status
                $appointment = Appointment::find($appointment->id);
                $appointment->update(['is_completed' => 1,'payment_status' => 1,'payment_type' => \App\Models\Appointment::FLUTTERWAVE]);

                DB::commit();
                session()->forget('appointmentflutterwaveData');
                return true;
            }
        }catch(Exception $e){
            DB::rollBack();
            session()->forget('appointmentflutterwaveData');
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function phonePePayment($input)
    {
        $amount = $input['appointment_charge'];

        $redirectbackurl = route('appointment.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('appointment.phonepe.callback'). '?' . http_build_query(['input' => $input]);

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
        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('appointment.phonepe.callback');

        config([
            'phonepe.merchantId' => $merchantId,
            'phonepe.merchantUserId' => $merchantUserId,
            'phonepe.env' => $baseUrl,
            'phonepe.saltKey' => $saltKey,
            'phonepe.saltIndex' => $saltIndex,
            'phonepe.callBackUrl' => $callbackurl,
        ]);

        $finalXHeader = hash('sha256','/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'].$saltKey).'###'.$saltIndex;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl.'/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'accept: application/json',
                'X-VERIFY: '.$finalXHeader,
                'X-MERCHANT-ID: '.$input['transactionId']
            ),
        ));

        $responses = curl_exec($curl);

        $response = json_decode($responses);

        curl_close($curl);

        try{
            DB::beginTransaction();

            $appointment = Appointment::create($input['input']);

            $appointmentTransaction = AppointmentTransaction::create([
                'transaction_id' => $input['transactionId'],
                'appointment_id' => $appointment->id,
                'payment_type' => $input['input']['payment_mode'],
                'amount' => $input['input']['appointment_charge'],
            ]);

            // update appoitment payment Status
            $appointment = Appointment::find($appointment->id);
            $appointment->update(['is_completed' => 1,'payment_status' => 1,'payment_type' => \App\Models\Appointment::PHONEPE]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }

    public function payStackPaymentSuccess($input)
    {
        try {
            DB::beginTransaction();

            $sessionData = session()->get('appointmentPayStackData');

            $appointment = Appointment::create($sessionData['data']);

            $appointmentTransaction = AppointmentTransaction::create([
                'transaction_id' => $input['data']['reference'],
                'appointment_id' => $appointment->id,
                'payment_type' => $sessionData['data']['payment_mode'],
                'amount' => $sessionData['data']['appointment_charge'],
            ]);

            // update appoitment payment Status
            $appointment = Appointment::find($appointment->id);
            $appointment->update(['is_completed' => 1,'payment_status' => 1,'payment_type' => \App\Models\Appointment::PAYSTACK]);

            DB::commit();
            session()->forget('appointmentPayStackData');
            return true;

        }catch (Exception $e) {
            DB::rollBack();
            session()->forget('appointmentPayStackData');
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
