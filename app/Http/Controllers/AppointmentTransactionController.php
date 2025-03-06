<?php

namespace App\Http\Controllers;

use Laracasts\Flash\Flash;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal;
use Illuminate\Http\RedirectResponse;
use App\Repositories\AppointmentTransactionRepository;
use Unicodeveloper\Paystack\Facades\Paystack;

class AppointmentTransactionController extends AppBaseController
{
    /** @var AppointmentTransactionRepository */
    private $appointmentTransactionRepository;

    public function __construct(AppointmentTransactionRepository $appointmentTransactionRepo)
    {
        $this->appointmentTransactionRepository = $appointmentTransactionRepo;

        config(['paystack.publicKey' => getPaymentCredentials('paystack_public_key'),
            'paystack.secretKey' => getPaymentCredentials('paystack_secret_key'),
            'paystack.paymentUrl' => 'https://api.paystack.co',
        ]);
    }

    public function  index() {
        return view('appointment_transaction.index');
    }

    public function appointmentStripePaymentSuccess(Request $request)
    {
        $this->appointmentTransactionRepository->appointmentStripePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('appointments.index'));
    }

    public function webAppointmentStripePaymentSuccess(Request $request)
    {
        $this->appointmentTransactionRepository->appointmentStripePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('appointment'));
    }

    public function appointmentRazorpayPayment(Request $request)
    {
        $result = $this->appointmentTransactionRepository->TransactionRazorpayPayment($request->all());

        return $this->sendResponse($result, 'order created');
    }

    public function appointmentRazorpayPaymentSuccess(Request $request)
    {
        $this->appointmentTransactionRepository->TransactionRazorpayPaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('appointments.index'));
    }

    public function webAppointmentRazorpayPayment(Request $request)
    {
        $result = $this->appointmentTransactionRepository->TransactionRazorpayPayment($request->all());

        return $this->sendResponse($result, 'order created');
    }

    public function WebAppointmentRazorpayPaymentSuccess(Request $request)
    {
        $this->appointmentTransactionRepository->TransactionRazorpayPaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->back();
    }

    public function paypalOnBoard(Request $request)
    {
        if (! in_array(strtoupper(getCurrentCurrency()), getPayPalSupportedCurrencies())) {
            if($request->get('appointment_id')){
                Appointment::find($request->get('appointment_id'))->delete();
            }
            return $this->sendError(__('messages.payment.currency_not_supported_paypal'));
        }
        $amount = $request->get('amount');
        $appointmentId = $request->get('appointment_id');

        $mode = getSelectedPaymentGateway('paypal_mode');
        $clientId = getSelectedPaymentGateway('paypal_client_id');
        $clientSecret = getSelectedPaymentGateway('paypal_secret');

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal();
        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $appointmentId,
                    'amount' => [
                        'value' => $amount,
                        'currency_code' => getCurrentCurrency(),
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('appointment.paypal.failed',['appointment_id' => $appointmentId]),
                'return_url' => route('appointment.paypal.success'),
            ],
        ];

        $order = $provider->createOrder($data);

        return response()->json(['url' => $order['links'][1]['href'], 'status' => 201]);
    }

    public function paypalSuccess(Request $request): RedirectResponse
    {

        $mode = getSelectedPaymentGateway('paypal_mode');
        $clientId = getSelectedPaymentGateway('paypal_client_id');
        $clientSecret = getSelectedPaymentGateway('paypal_secret');

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal;

        $provider->getAccessToken();

        $token = $request->get('token');

        $response = $provider->capturePaymentOrder($token);

        $this->appointmentTransactionRepository->paypalPaymentSuccess($response);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('appointments.index'));
    }

    public function paypalFailed(Request $request): RedirectResponse
    {
        $appointmentId = $request['appointment_id'];
        if($appointmentId){
            Appointment::find($appointmentId)->delete();
        }
        Flash::error(__('messages.flash.your_payment_failed'));

        return redirect(route('appointments.index'));
    }

    public function webAppointmentPaypalOnBoard(Request $request)
    {
        if (! in_array(strtoupper(getCurrentCurrency()), getPayPalSupportedCurrencies())) {
            return $this->sendError(__('messages.flash.currency_not_supported_paypal'));
        }
        $amount = $request->get('amount');
        $appointmentId = $request->get('appointment_id');

        $mode = getSelectedPaymentGateway('paypal_mode');
        $clientId = getSelectedPaymentGateway('paypal_client_id');
        $clientSecret = getSelectedPaymentGateway('paypal_secret');

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal();
        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $appointmentId,
                    'amount' => [
                        'value' => $amount,
                        'currency_code' => getCurrentCurrency(),
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('web.appointment.paypal.failed',['appointment_id' => $appointmentId]),
                'return_url' => route('web.appointment.paypal.success'),
            ],
        ];

        $order = $provider->createOrder($data);

        return response()->json(['url' => $order['links'][1]['href'], 'status' => 201]);
    }

    public function webAppointmentPaypalSuccess(Request $request): RedirectResponse
    {

        $mode = getSelectedPaymentGateway('paypal_mode');
        $clientId = getSelectedPaymentGateway('paypal_client_id');
        $clientSecret = getSelectedPaymentGateway('paypal_secret');

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal;

        $provider->getAccessToken();

        $token = $request->get('token');

        $response = $provider->capturePaymentOrder($token);

        $this->appointmentTransactionRepository->paypalPaymentSuccess($response);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('appointment'));
    }

    public function webAppointmentPaypalFailed(Request $request): RedirectResponse
    {
        $appointmentId = $request['appointment_id'];
        if($appointmentId){
            Appointment::find($appointmentId)->delete();
        }
        Flash::error(__('messages.payment.payment_failed'));

        return redirect(route('appointment'));
    }

    public function appointmentRazorPayPaymentFailed(Request $request)
    {
        $appointment = Appointment::orderBy('created_at', 'desc')->latest()->first();

        $appointment->delete();

        return $this->sendSuccess(__('messages.payment.payment_failed'));
    }

    public function appointmentStripeFailed(Request $request)
    {
        $appointmentId = $request['appointment_id'];
        if($appointmentId){
            Appointment::find($appointmentId)->delete();
        }
        Flash::error(__('messages.payment.payment_failed'));

        return redirect(route('appointments.index'));
    }

    public function webAppointmentStripeFailed(Request $request)
    {
        $appointmentId = $request['appointment_id'];
        if($appointmentId){
            Appointment::find($appointmentId)->delete();
        }
        Flash::error(__('messages.payment.payment_failed'));

        return redirect(route('appointment'));
    }

    public function webAppointmentRazorPayPaymentFailed(Request $request)
    {
        $appointment = Appointment::orderBy('created_at', 'desc')->latest()->first();

        $appointment->delete();
        return $this->sendSuccess(['message' => __('messages.payment.payment_failed'), 'url' => route('appointment')]);
    }

    public function flutterWaveSuccess(Request $request)
    {
        $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
        $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

        if(!$flutterwavePublicKey && !$flutterwaveSecretKey){
            return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
        }

        config([
            'flutterwave.publicKey' => $flutterwavePublicKey,
            'flutterwave.secretKey' => $flutterwaveSecretKey,
        ]);

        if($request['status'] == 'cancelled'){
            if(isset($request->input['web_appointment']) && $request->input['web_appointment'] == true){

                Flash::error(__('messages.payment.payment_failed'));

                return redirect()->route('appointment');
            }

            Flash::error(__('messages.payment.payment_failed'));

            return redirect()->route('appointments.index');
        }

        $this->appointmentTransactionRepository->flutterwavePaymentSuccess($request->all());

        if(isset($request->input['web_appointment']) && $request->input['web_appointment'] == true){

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            return redirect()->route('appointment');
        }

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->route('appointments.index');
    }

    public function phonePePaymentSuccess(Request $request)
    {
        $this->appointmentTransactionRepository->phonePePaymentSuccess($request->all());

        if(isset($request->input['web_appointment']) && $request->input['web_appointment'] == true){

            Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

            return redirect()->route('appointment');
        }
        
        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect()->route('appointments.index');
    }

    public function paystackPayment(Request $request)
    {
        if(!in_array(strtoupper(getCurrentCurrency()),getPayStackSupportedCurrencies())){

            if(isset($request->data['web_appointment']) && $request->data['web_appointment'] == true){

                Flash::error(__('messages.payment.paystack_support_zar'));

                return redirect()->route('appointment');
            }

            Flash::error(__('messages.payment.paystack_support_zar'));

            return redirect()->route('appointments.index');
        }

        session(['appointmentPayStackData' => $request->all()]);

        $data = $request->data;
        $amount = $data['appointment_charge'];

        try {
            $request->merge([
                'email' => getLoggedInUser()->email,
                'orderID' => $data['patient_id'],
                'amount' => $amount * 100,
                'quantity' => 1,
                'currency' => strtoupper(getCurrentCurrency()),
                'reference' => Paystack::genTranxRef(),
                'metadata' => json_encode(['data' => $data]),
            ]);
            $authorizationUrl = Paystack::getAuthorizationUrl();

            return $authorizationUrl->redirectNow();
        } catch (\Exception $e) {
            session()->forget('appointmentPayStackData');
            if(isset($request->data['web_appointment']) && $request->data['web_appointment'] == true){

                Flash::error(__('messages.payment.payment_failed'));

                return redirect()->route('appointment');
            }

            Flash::error(__('messages.payment.payment_failed'));

            return redirect()->route('appointments.index');
        }
    }
}
