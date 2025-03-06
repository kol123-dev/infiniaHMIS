<?php

namespace App\Repositories;

use App\Models\ManualBillPayment;
use App\Models\Bill;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use KingFlamez\Rave\Facades\Rave as Flutterwave;


class ManualBillPaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'transaction_id',
        'payment_type',
        'amount',
        'bill_id',
        'status',
        'meta',
        'is_manual_payment',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return ManualBillPayment::class;
    }

    public function create($input)
    {
        $bill = Bill::find($input['id']);

        if(!empty($bill)){
            ManualBillPayment::create([
                'payment_type' => $input['payment_type'],
                'amount' => $bill->amount,
                'bill_id' => $bill->id,
                'status' => $input['payment_type'] == 1 ? 0 : 1,
                'is_manual_payment' => $input['payment_type'] == 1 ? 1 : 0,
            ]);
            $bill->update(['payment_mode' => $input['payment_type']]);
        }

        return true;
    }

    public function updateTransaction($input, $id)
    {
        $billTransaction = ManualBillPayment::with('bill')->find($id);

        if ($input['payment_status'] == ManualBillPayment::Approved) {
            $billTransaction->update(['status' => ManualBillPayment::Approved]);
            $billTransaction->bill()->update(['status' => ManualBillPayment::Approved]);
        }else{
            $billTransaction->update(['status' => ManualBillPayment::Rejected]);
            $billTransaction->bill()->update(['status' => ManualBillPayment::Rejected,'payment_mode' => null]);
        }
    }

    // Make stripe payment
    public function createStripeSession($patientBill)
    {
        setStripeApiKey();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $patientBill->patient->patientUser->email,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Payment for Patient bill',
                        ],
                        'unit_amount' => in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies()) ? $patientBill->amount : $patientBill->amount * 100,
                        'currency' => strtoupper(getCurrentCurrency()),
                    ],
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => $patientBill->id,
            'mode' => 'payment',
            'success_url' => route('stripe.payment.success').'?session_id={CHECKOUT_SESSION_ID}',
        ]);

        $result = [
            'sessionId' => $session['id'],
        ];

        return $result;
    }

    //after stripe payment show stripe success
    public function stripePaymentSuccess($sessionId)
    {
        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException(__('messages.bill.session_id_required'));
        }
        setStripeApiKey();

        $sessionData = \Stripe\Checkout\Session::retrieve($sessionId);
        $bill = Bill::find($sessionData->client_reference_id);

        try {
            DB::beginTransaction();

            if(!empty($bill)){
                ManualBillPayment::create([
                    'transaction_id' => null,
                    'payment_type' => Bill::Stripe,
                    'amount' => $bill->amount,
                    'bill_id' => $bill->id,
                    'status' => 1,
                    'meta' => null,
                    'is_manual_payment' => 0,
                ]);
                $bill->update(['payment_mode' => Bill::Stripe, 'status' => '1']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

    }

    // Make razorpay payment
    public function razorpayPayment($billId)
    {
        $patientBill = bill::with('patient.patientUser')->whereId($billId)->first();
        $amount = $patientBill->amount;

        // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret_key'));
        $api = new Api(getPaymentCredentials('razorpay_key'), getPaymentCredentials('razorpay_secret'));

        $orderData = [
            'receipt' => '1',
            'amount' => $amount * 100, // 100 = 1 rupees
            'currency' => strtoupper(getCurrentCurrency()),
            'notes' => [
                'billID' => $billId,
            ],
        ];
        $razorpayOrder = $api->order->create($orderData);
        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $amount;

        return $data;
    }

    // after razorpay payment show razorpay success
    public function razorpayPaymentSuccess($input)
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
                $billId = $payment['notes']['billID'];
                $bill = Bill::find($billId);

                if(!empty($bill)){
                    ManualBillPayment::create([
                        'transaction_id' => null,
                        'payment_type' => Bill::Razorpay,
                        'amount' => $bill->amount,
                        'bill_id' => $bill->id,
                        'status' => 1,
                        'meta' => null,
                        'is_manual_payment' => 0,
                    ]);
                    $bill->update(['payment_mode' => Bill::Razorpay, 'status' => '1']);
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw new UnprocessableEntityHttpException($e->getMessage());
            }

            return false;
        }
    }

    public function flutterWavePayment($input)
    {
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $amount,
            'email' => getLoggedInUser()->email,
            'tx_ref' => $reference,
            'currency' => getCurrentCurrency(),
            'redirect_url' => route('flutterwave.success'),
            'customer' => [
                'email' => getLoggedInUser()->email,
            ],
            "customizations" => [
                'title' => 'Bill',
                'logo' => asset(getLogoUrl()),
            ],
            'meta' => [
                'email' => getLoggedInUser()->email,
                'currency_symbol' => getCurrentCurrency(),
                'patient_bill_id' => $patientBill->id,
                'payment_type' => $input['payment_type'],
                'amount' => $amount,
            ]
        ];

        $payment = Flutterwave::initializePayment($data);

        if ($payment['status'] !== 'success') {
            return redirect()->back();
        }

        $url = $payment['data']['link'];
    }
    public function phonePePayment($input)
    {
        $patientBill = bill::with('patient.patientUser')->whereId($input['id'])->first();

        $amount =  $patientBill->amount;
        $redirectbackurl = route('billing.phonepe.callback'). '?' . http_build_query(['input' => $input]);

        $merchantId = getPaymentCredentials('phonepe_merchant_id');
        $merchantUserId = getPaymentCredentials('phonepe_merchant_id');
        $merchantTransactionId = getPaymentCredentials('phonepe_merchant_transaction_id');
        $baseUrl = getPaymentCredentials('phonepe_env') == 'production' ? 'https://api.phonepe.com/apis/hermes' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $saltKey = getPaymentCredentials('phonepe_salt_key');
        $saltIndex = getPaymentCredentials('phonepe_salt_index');
        $callbackurl = route('billing.phonepe.callback'). '?' . http_build_query(['input' => $input]);
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

    public function flutterwavePaymentSuccess($input)
    {
        try {
            DB::beginTransaction();

            if ($input['status'] ==  'successful')
            {
                $transactionID = Flutterwave::getTransactionIDFromCallback();
                $data = Flutterwave::verifyTransaction($transactionID);

                $billId = $data['data']['meta']['patient_bill_id'];
                $bill = Bill::find($billId);

                if(!empty($bill)){
                    ManualBillPayment::create([
                        'transaction_id' => null,
                        'payment_type' => Bill::Flutterwave,
                        'amount' => $bill->amount,
                        'bill_id' => $bill->id,
                        'status' => 1,
                        'meta' => null,
                        'is_manual_payment' => 0,
                    ]);
                    $bill->update(['payment_mode' => Bill::Flutterwave, 'status' => '1']);
                }

                DB::commit();
            }


        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }
    public function billPhonePePaymentSuccess($input)
    {
        try{
            DB::beginTransaction();

            $bill = Bill::find($input['input']['id']);

            if(!empty($bill)){
                $payment = ManualBillPayment::create([
                    'transaction_id' => $input['transactionId'],
                    'payment_type' => Bill::Paystack,
                    'amount' => $bill->amount,
                    'bill_id' => $bill->id,
                    'status' => 1,
                    'meta' => null,
                    'is_manual_payment' => 0,
                ]);
                $bill->update(['payment_mode' => Bill::PhonePe, 'status' => '1']);
                // ManualBillPayment::create([
                //     'transaction_id' => $input['transactionId'],
                //     'payment_type' => Bill::PhonePe,
                //     $bill->update(['payment_mode' => Bill::PhonePe, 'status' => '1']);
                // }
            }

                DB::commit();

                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
            return false;
    }

    public function PaystackPaymentSucess($response)
    {
        $billId = $response['data']['metadata']['bill_id'];

        $bill = Bill::find($billId);
        try {
            DB::beginTransaction();
            //Create Transaction Here for Paystack
            if(!empty($bill)){
                $payment = ManualBillPayment::create([
                    'transaction_id' => null,
                    'payment_type' => Bill::Paystack,
                    'amount' => $bill->amount,
                    'bill_id' => $bill->id,
                    'status' => 1,
                    'meta' => null,
                    'is_manual_payment' => 0,
                ]);

                $bill->update(['payment_mode' => Bill::Paystack, 'status' => '1']);

            }
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
