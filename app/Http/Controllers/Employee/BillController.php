<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\AppBaseController;
use App\Models\Bill;
use App\Queries\BillDataTable;
use App\Repositories\BillRepository;
use \PDF;
use Carbon\Carbon;
use Datatables;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Response;

class BillController extends AppBaseController
{
    /** @var BillRepository */
    private $billRepository;

    public function __construct(BillRepository $billRepo)
    {
        $this->billRepository = $billRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new BillDataTable())->get($request->only(['status'])))
                ->addColumn('patientImageUrl', function (Bill $bill) {
                    return $bill->patient->user->image_url;
                })->make(true);
        }

        return view('employees.bills.index');
    }

    public function show(Bill $bill)
    {
        if (checkRecordAccess($bill->patient_id)) {
            return view('errors.404');
        } else {
            $bill = Bill::with(['billItems.medicine', 'patient'])->find($bill->id);
            $bill = Bill::with(['billItems.medicine', 'patient', 'patientAdmission'])->find($bill->id);
            $admissionDate = Carbon::parse($bill->patientAdmission->admission_date);
            $dischargeDate = Carbon::parse($bill->patientAdmission->discharge_date);
            $bill->totalDays = $admissionDate->diffInDays($dischargeDate) + 1;

            return view('employees.bills.show')->with('bill', $bill);
        }
    }

    public function convertToPdf(Bill $bill)
    {
        $bill->billItems;
        $data = $this->billRepository->getSyncListForCreate();
        $data['bill'] = $bill;
        $pdf = PDF::loadView('bills.bill_pdf', $data);

        return $pdf->stream('bill.pdf');
    }
}
