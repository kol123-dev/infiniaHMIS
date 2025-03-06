<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\ManualBillPayment;
use App\Repositories\ManualBillPaymentRepository;
use Exception;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Unicodeveloper\Paystack\Facades\Paystack;

class ManualBillPaymentController extends AppBaseController
{

    /** @var ManualBillPaymentRepository */
    private $manualBillPaymentRepository;

    public function __construct(ManualBillPaymentRepository $manualBillPaymentRepository)
    {
        $this->manualBillPaymentRepository = $manualBillPaymentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('manual_bill_payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if ($request->payment_type == Bill::Stripe) {
            $patientBill = bill::with('patient.patientUser')->whereId($input['id'])->first();

            $result = $this->manualBillPaymentRepository->createStripeSession($patientBill);

            return $this->sendResponse([
                'bill_id' => $patientBill->id,
                'payment_type' => $input['payment_type'],
                $result
            ], 'Stripe session created successfully');
        } elseif ($request->payment_type == Bill::Razorpay) {

            return $this->sendResponse([
                'payment_type' => $input['payment_type'],
                'bill_id' => $input['id'],
            ], 'Razorpay session created successfully');
        } elseif ($request->payment_type == Bill::Flutterwave) {

            if (!in_array(strtoupper(getCurrentCurrency()), getFlutterWaveSupportedCurrencies())) {
                return $this->sendError(__('messages.payment.flutterwave_not_support'));
            }

            $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
            $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

            if (!$flutterwavePublicKey && !$flutterwaveSecretKey) {
                return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
            }

            config([
                'flutterwave.publicKey' => $flutterwavePublicKey,
                'flutterwave.secretKey' => $flutterwaveSecretKey,
            ]);

            $result = $this->manualBillPaymentRepository->flutterWavePayment($input);

            return $this->sendResponse(['url' => $result, 'payment_type' => $input['payment_type']], 'FlutterWave created successfully');
        } elseif ($request->payment_type == Bill::PhonePe) {

            if (strtoupper(getCurrentCurrency()) != 'INR') {
                return $this->sendError(__('messages.payment.phonepe_support_inr'));
            }

            $result = $this->manualBillPaymentRepository->phonePePayment($input);

            return $this->sendResponse(['url' => $result, 'payment_type' => $input['payment_type']], 'PhonePe created successfully');
        } elseif ($request->payment_type == Bill::Paystack) {

            return $this->sendResponse([
                'payment_type' => $input['payment_type'],
                'bill_id' => $input['id'],
            ], 'Paystack session created successfully');
        } else {
            $this->manualBillPaymentRepository->create($input);

            return $this->sendSuccess(__('messages.bill.paymentrequest_sent'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $this->manualBillPaymentRepository->updateTransaction($input, $id);

        return $this->sendSuccess(__('messages.common.status') . ' ' . __('messages.common.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');

        $this->manualBillPaymentRepository->stripePaymentSuccess($sessionId);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('employee.bills.index'));
    }

    public function onBoard(Request $request)
    {
        $billId = $request->bill_id;

        $data = $this->manualBillPaymentRepository->razorpayPayment($billId);

        return $this->sendResponse($data, 'order created');
    }

    public function razorpayPaymentSuccess(Request $request)
    {
        $input = $request->all();

        $this->manualBillPaymentRepository->razorpayPaymentSuccess($input);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('employee.bills.index'));
    }

    public function flutterwavePaymentSuccess(Request $request)
    {
        $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
        $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

        if (!$flutterwavePublicKey && !$flutterwaveSecretKey) {
            return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
        }

        config([
            'flutterwave.publicKey' => $flutterwavePublicKey,
            'flutterwave.secretKey' => $flutterwaveSecretKey,
        ]);

        if ($request['status'] == 'cancelled') {
            Flash::error(__('messages.payment.payment_failed'));

            return redirect()->back();
        }
        $this->manualBillPaymentRepository->flutterwavePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->back();
    }
    public function billPhonePePaymentSuccess(Request $request)
    {
        $this->manualBillPaymentRepository->billPhonePePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->back();
    }


    public function ManualPaystackOnBoard(Request $request)
    {
        if (!in_array(strtoupper(getCurrentCurrency()), getPayStackSupportedCurrencies())) {
            Flash::error(__('messages.payment.paystack_support_zar'));

            return redirect(route('employee.bills.index'));
        }
        $bill = Bill::find($request->bill_id);

        try {
            $request->merge([
                'email' => getLoggedInUser()->email, // email of recipients
                'orderID' => $request->bill_id, // anything
                'amount' => $bill->amount * 100,
                'quantity' => 1, // always 1
                'currency' => 'ZAR',
                'reference' => Paystack::genTranxRef(),
                'metadata' => ['bill_id' =>  $request->bill_id]
            ]);

            $authorizationUrl = Paystack::getAuthorizationUrl();

            return $authorizationUrl->redirectNow();
        } catch (\Exception $e) {
            Flash::error(__('messages.payment.payment_failed'));

            return Redirect::back()->withMessage([
                'msg' => __('messages.payment.paystack_token_expired'),
                'type' => 'error',
            ]);
        }
    }

    public function ManualPaystackSuccess(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        $this->manualBillPaymentRepository->PaystackPaymentSucess($paymentDetails);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('employee.bills.index'));
    }
}
