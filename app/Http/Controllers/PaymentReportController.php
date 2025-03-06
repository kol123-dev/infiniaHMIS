<?php

namespace App\Http\Controllers;

use App\Exports\PaymentReportExport;
use App\Models\Account;
use App\Models\Payment;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class PaymentReportController extends Controller
{
    public function index()
    {
        $accountTypes = Account::ACCOUNT_TYPES;

        return view('payment_reports.index', compact('accountTypes'));
    }

    public function paymentReportExport()
    {
        $paymentReport = Payment::with('accounts')->get();
        if (!$paymentReport) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('payment.reports'));
        }
        return Excel::download(new PaymentReportExport, 'payments-reports-' . time() . '.xlsx');
    }
}
