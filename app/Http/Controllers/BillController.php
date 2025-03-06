<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use App\Models\Patient;
use App\Repositories\BillRepository;
use \PDF;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Models\Setting;

class BillController extends AppBaseController
{
    /** @var BillRepository */
    private $billRepository;

    public function __construct(BillRepository $billRepo)
    {
        $this->billRepository = $billRepo;
    }

    public function index()
    {
        $setting = Setting::pluck('key','value');

        return view('bills.index',compact('setting'));
    }

    public function create()
    {
        $data = $this->billRepository->getSyncList(false);

        return view('bills.create')->with($data);
    }

    public function store(CreateBillRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
            $birthDate = $patientId->patientUser->dob;
            $billDate = Carbon::parse($input['bill_date'])->toDateString();

            if (! empty($birthDate) && $billDate < $birthDate) {
                return $this->sendError(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));
            }

            $bill = $this->billRepository->saveBill($request->all());
            $this->billRepository->saveNotification($input);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($bill, __('messages.bill.bill').' '.__('messages.common.saved_successfully'));
    }

    public function show(Bill $bill)
    {
        $bill = Bill::with(['billItems.medicine', 'patient', 'patientAdmission'])->find($bill->id);

        if ($bill->patientAdmission) {
            $admissionDate = Carbon::parse($bill->patientAdmission->admission_date);
            $dischargeDate = Carbon::parse($bill->patientAdmission->discharge_date);
            $bill->totalDays = $admissionDate->diffInDays($dischargeDate) + 1;
        }

        return view('bills.show')->with('bill', $bill);
    }

    public function edit(Bill $bill)
    {
        $bill->billItems;
        $isEdit = true;
        $data = $this->billRepository->getSyncList($isEdit);
        $data['bill'] = $bill;

        return view('bills.edit')->with($data);
    }

    public function update(Bill $bill, UpdateBillRequest $request)
    {
        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $billDate = Carbon::parse($input['bill_date'])->toDateString();

        if (! empty($birthDate) && $billDate < $birthDate) {
            return $this->sendError(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));
        }

        $bill = $this->billRepository->updateBill($bill->id, $request->all());

        return $this->sendResponse($bill, __('messages.bill.bill').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Bill $bill)
    {
        $this->billRepository->delete($bill->id);

        return $this->sendSuccess(__('messages.bill.bill').' '.__('messages.common.deleted_successfully'));
    }

    public function getPatientAdmissionDetails(Request $request)
    {
        $inputs = $request->all();
        $patientAdmissionDetails = $this->billRepository->patientAdmissionDetails($inputs);

        return $this->sendResponse($patientAdmissionDetails, 'Details retrieved successfully.');
    }

    public function convertToPdf(Bill $bill)
    {
        $bill->billItems;
        $data = $this->billRepository->getSyncListForCreate($bill->id);
        $data['bill'] = $bill;
        $pdf = PDF::loadView('bills.bill_pdf', $data);

        return $pdf->stream('bill.pdf');
    }
}
