<?php

namespace App\Http\Controllers;

use App\Exports\PaymentExport;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends AppBaseController
{
    /** @var PaymentRepository */
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepository = $paymentRepo;
    }

    public function index()
    {
        return view('payments.index');
    }

    public function create()
    {
        $accounts = $this->paymentRepository->getAccounts();

        return view('payments.create', compact('accounts'));
    }

    public function store(CreatePaymentRequest $request)
    {
        $input = $request->all();
        $input['amount'] = removeCommaFromNumbers($input['amount']);
        $payment = $this->paymentRepository->create($input);

        Flash::success(__('messages.payment.payment') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('payments.index'));
    }

    public function show(Payment $payment)
    {
        return view('payments.show')->with('payment', $payment);
    }

    public function edit(Payment $payment)
    {
        $accounts = $this->paymentRepository->getAccounts();

        return view('payments.edit', compact('accounts', 'payment'));
    }

    public function update(Payment $payment, UpdatePaymentRequest $request)
    {
        $input = $request->all();
        $input['amount'] = removeCommaFromNumbers($input['amount']);
        $payment = $this->paymentRepository->update($input, $payment->id);

        Flash::success(__('messages.payment.payment') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('payments.index'));
    }

    public function destroy(Payment $payment)
    {
        $this->paymentRepository->delete($payment->id);

        return $this->sendSuccess(__('messages.payment.payment') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function paymentExport()
    {
        $payments = Payment::with('account')->get();
        if (!$payments) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('payments.index'));
        }
        return Excel::download(new PaymentExport, 'payments-' . time() . '.xlsx');
    }

    public function showModal(Payment $payment)
    {
        $payment->load('account');
        $currency = $payment->currency_symbol ? strtoupper($payment->currency_symbol) : strtoupper(getCurrentCurrency());
        $payment['amount'] = checkNumberFormat($payment->amount, $currency);

        return $this->sendResponse($payment, 'Payment Retrieved Successfully.');
    }
}
