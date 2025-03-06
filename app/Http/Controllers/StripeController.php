<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use App\Repositories\StripeRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class StripeController extends AppBaseController
{
    /**
     * @var StripeRepository
     */
    private $stripeRepository;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepository = $stripeRepository;
    }

    public function createSession(Request $request)
    {
        $amount = $request->get('amount');
        $ipdNumber = $request->get('ipdNumber');
        $ipdPaientId = IpdPatientDepartment::whereIpdNumber($ipdNumber)->first()->id;

        if (in_array(strtoupper(getCurrentCurrency()), zeroDecimalCurrencies())) {
            $final_amount = $amount;
        } else {
            $final_amount = $amount * 100;
        }

        $user = $request->user();
        $userEmail = $user->email;
        setStripeApiKey();
        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $userEmail,
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'BILL OF PATIENT with IPD #'.$ipdNumber,
                        ],
                        'unit_amount' => $final_amount,
                        'currency' => getCurrentCurrency(),
                    ],
                    'quantity' => 1,
                    'description' => 'BILL OF PATIENT with IPD #'.$ipdNumber,
                ],
            ],
            'client_reference_id' => $ipdPaientId,
            'mode' => 'payment',
            'success_url' => url('payment-success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('failed-payment?error=payment_cancelled'),
        ]);
        $result = [
            'sessionId' => $session['id'],
        ];

        return $this->sendResponse($result, 'Session created successfully.');
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (empty($sessionId)) {
            throw new UnprocessableEntityHttpException('session_id required');
        }

        $this->stripeRepository->patientPaymentSuccess($sessionId);

        Flash::success(__('messages.payment.your_payment_is_successfully_completed'));

        return redirect(route('patient.ipd'));
    }

    public function handleFailedPayment()
    {
        Flash::error(__('messages.payment.payment_failed'));

        return redirect(route('patient.ipd'));
    }
}
