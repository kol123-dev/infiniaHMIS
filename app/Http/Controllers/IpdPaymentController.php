<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdPaymentRequest;
use App\Http\Requests\UpdateIpdPaymentRequest;
use App\Models\IpdPayment;
use App\Queries\IpdPaymentDataTable;
use App\Repositories\IpdPaymentRepository;
use Exception;
use DataTables;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Unicodeveloper\Paystack\Facades\Paystack;

class IpdPaymentController extends AppBaseController
{
    /** @var IpdPaymentRepository */
    private $ipdPaymentRepository;

    public function __construct(IpdPaymentRepository $ipdPaymentRepo)
    {
        $this->ipdPaymentRepository = $ipdPaymentRepo;

        config(['paystack.publicKey' => getPaymentCredentials('paystack_public_key'),
        'paystack.secretKey' => getPaymentCredentials('paystack_secret_key'),
        'paystack.paymentUrl' => 'https://api.paystack.co',
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdPaymentDataTable())->get($request->id))->make(true);
        }
    }

    public function store(CreateIpdPaymentRequest $request)
    {
        try {
            $input = $request->all();

            if($input['payment_mode'] == IpdPayment::PAYMENT_MODES_STRIPE){

                $result = $this->ipdPaymentRepository->stripeSession($input);

                return $this->sendResponse([
                    'ipdID' => $input['ipd_patient_department_id'],
                    'payment_type' => $input['payment_mode'],
                    $result
                ],'Stripe session created successfully');

            }elseif($input['payment_mode'] == IpdPayment::PAYMENT_MODES_RAZORPAY){

                return $this->sendResponse([
                    'ipdID' => $input['ipd_patient_department_id'],
                    'amount' => $input['amount'],
                    'payment_type' => $input['payment_mode'],
                ],'Razorpay session created successfully');

        }elseif($input['payment_mode'] == IpdPayment::PAYMENT_MODES_FLUTTERWAVE){

            if(!in_array(strtoupper(getCurrentCurrency()),getFlutterWaveSupportedCurrencies())){
                    return $this->sendError(__('messages.payment.flutterwave_not_support'));
            }

            $flutterwavePublicKey = getPaymentCredentials('flutterwave_public_key');
            $flutterwaveSecretKey = getPaymentCredentials('flutterwave_secret_key');

            if(!$flutterwavePublicKey && !$flutterwaveSecretKey){
                return $this->sendError(__('messages.flutterwave.set_flutterwave_credential'));
            }

            config([
                'flutterwave.publicKey' => $flutterwavePublicKey,
                'flutterwave.secretKey' => $flutterwaveSecretKey,
            ]);

           $result = $this->ipdPaymentRepository->flutterWavePayment($input);

           return $this->sendResponse(['url' => $result,'payment_type' => $input['payment_mode']],'FlutterWave created successfully');
            }elseif($input['payment_mode'] == IpdPayment::PAYMENT_MODES_PHONEPE){
                // Create payment in phonePe wallet
                if (strtoupper(getCurrentCurrency()) != 'INR') {
                    return $this->sendError(__('messages.payment.phonepe_support_inr'));
                }

                $result = $this->ipdPaymentRepository->phonePePayment($input);

                return $this->sendResponse(['url' => $result,'payment_type' => $input['payment_mode']],'PhonePe created successfully');
        }elseif($input['payment_mode'] == IpdPayment::PAYMENT_MODES_PAYSTACK){

            return $this->sendResponse([
                'ipdID' => $input['ipd_patient_department_id'],
                'amount' => $input['amount'],
                'payment_type' => $input['payment_mode'],
                'notes' => $input['notes'],
            ],'Razorpay session created successfully');

        }else{
            $this->ipdPaymentRepository->store($input);
        }

            return $this->sendSuccess(__('messages.ipd_payment').' '.__('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(IpdPayment $ipdPayment)
    {
        return $this->sendResponse($ipdPayment, 'IPD Payment retrieved successfully.');
    }

    public function update(IpdPayment $ipdPayment, UpdateIpdPaymentRequest $request)
    {
        $this->ipdPaymentRepository->updateIpdPayment($request->all(), $ipdPayment->id);

        return $this->sendSuccess(__('messages.ipd_payment').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdPayment $ipdPayment)
    {
        $this->ipdPaymentRepository->deleteIpdPayment($ipdPayment->id);

        return $this->sendSuccess(__('messages.ipd_payment').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(IpdPayment $ipdPayment)
    {
        $media = $ipdPayment->getMedia(IpdPayment::IPD_PAYMENT_PATH)->first();

        if ($media != null) {
            $media = $media->id;
            $mediaItem = Media::find($media);

            return $mediaItem;
        }

        return '';
    }

    public function ipdStripePaymentSuccess(Request $request)
    {
        $this->ipdPaymentRepository->ipdStripePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        if(getLoggedinPatient()){
            return redirect(route('patient.ipd'));
        }

        return redirect(route('ipd.patient.index'));
    }

    public function ipdRazorpayPayment(Request $request)
    {
        $result = $this->ipdPaymentRepository->razorpayPayment($request->all());

       return $this->sendResponse($result, 'order created');
    }

    public function ipdRazorpayPaymentSuccess(Request $request)
    {
        $this->ipdPaymentRepository->ipdRazorpayPaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        if(getLoggedinPatient()){
            return redirect(route('patient.ipd'));
        }

        return redirect(route('ipd.patient.index'));
    }

    public function ipdFlutterwavePaymentSuccess(Request $request)
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
            Flash::error(__('messages.payment.payment_failed'));

            if(getLoggedinPatient()){
                return redirect(route('patient.ipd'));
            }

            return redirect(route('ipd.patient.index'));
        }
        $this->ipdPaymentRepository->ipdFlutterwavePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        if(getLoggedinPatient()){
            return redirect(route('patient.ipd'));
        }

        return redirect(route('ipd.patient.index'));
    }

    public function phonePePaymentSuccess(Request $request)
    {
        $this->ipdPaymentRepository->phonePePaymentSuccess($request->all());

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        if(getLoggedinPatient()){
            return redirect(route('patient.ipd'));
        }

        return redirect(route('ipd.patient.index'));
    }

    public function ipdPaystackPayment(Request $request)
    {
        if(!in_array(strtoupper(getCurrentCurrency()),getPayStackSupportedCurrencies())){
            Flash::error(__('messages.payment.paystack_support_zar'));

            if(getLoggedinPatient()){
                return redirect(route('patient.ipd'));
            }

            return redirect(route('ipd.patient.index'));
        }
        $amount = $request->amount;
        $ipdNumber = $request->ipdNumber;

        try {
            $request->merge([
                'email' => getLoggedInUser()->email, // email of recipients
                'orderID' => $ipdNumber, // anything
                'amount' => $amount * 100,
                'quantity' => 1, // always 1
                'currency' => strtoupper(getCurrentCurrency()),
                'reference' => Paystack::genTranxRef(),
                'metadata' => json_encode(['ipd_patient_id' => $ipdNumber,'notes' => $request->notes]), // this should be related data
            ]);

            $authorizationUrl = Paystack::getAuthorizationUrl();

            return $authorizationUrl->redirectNow();
        } catch (\Exception $e) {
            Flash::error(__('messages.payment.payment_failed'));

            if(getLoggedinPatient()){
                return redirect(route('patient.ipd'));
            }

            return redirect(route('ipd.patient.index'));
        }
    }
}
