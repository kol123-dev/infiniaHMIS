<?php

namespace App\Http\Controllers;

use App\Exports\InsuranceExport;
use App\Http\Requests\CreateInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Models\Insurance;
use App\Models\PatientAdmission;
use App\Repositories\InsuranceRepository;
use DB;
use Exception;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class InsuranceController extends AppBaseController
{
    /** @var InsuranceRepository */
    private $insuranceRepository;

    public function __construct(InsuranceRepository $insuranceRepo)
    {
        $this->insuranceRepository = $insuranceRepo;
    }

    public function index()
    {
        $data['statusArr'] = Insurance::STATUS_ARR;

        return view('insurances.index', $data);
    }

    public function create()
    {
        return view('insurances.create');
    }

    public function store(CreateInsuranceRequest $request)
    {
        $input = $request->all();
        $input['service_tax'] = removeCommaFromNumbers($input['service_tax']);
        $input['hospital_rate'] = removeCommaFromNumbers($input['hospital_rate']);
        $input['status'] = isset($input['status']) ? 1 : 0;
        try {
            DB::beginTransaction();
            $insurance = $this->insuranceRepository->store($input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Flash::error($e->getMessage());

            return redirect(route('insurances.store'));
        }

        Flash::success(__('messages.insurance.insurance') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('insurances.index'));
    }

    public function show(Insurance $insurance)
    {
        $diseases = $this->insuranceRepository->getInsuranceDisease($insurance->id);

        return view('insurances.show', compact('diseases', 'insurance'));
    }

    public function edit(Insurance $insurance)
    {
        $diseases = $this->insuranceRepository->getInsuranceDisease($insurance->id);

        return view('insurances.edit', compact('diseases', 'insurance'));
    }

    public function update(Insurance $insurance, UpdateInsuranceRequest $request)
    {
        $input = $request->all();
        $input['service_tax'] = removeCommaFromNumbers($input['service_tax']);
        $input['hospital_rate'] = removeCommaFromNumbers($input['hospital_rate']);
        $input['status'] = isset($input['status']) ? 1 : 0;
        try {
            DB::beginTransaction();
            $insurance = $this->insuranceRepository->update($insurance, $input);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        Flash::success(__('messages.insurance.insurance') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('insurances.index'));
    }

    public function destroy(Insurance $insurance)
    {
        $insuranceModel = [
            PatientAdmission::class,
        ];
        $result = canDelete($insuranceModel, 'insurance_id', $insurance->id);

        if ($result) {
            return $this->sendError(__('messages.insurance.insurance') . ' ' . __('messages.common.cant_be_deleted'));
        }

        try {
            $this->insuranceRepository->delete($insurance->id);

            return $this->sendSuccess(__('messages.insurance.insurance') . ' ' . __('messages.common.deleted_successfully'));
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), $exception->getCode());
        }
    }

    public function activeDeactiveInsurance($id)
    {
        $insurance = Insurance::find($id);
        $insurance->status = ! $insurance->status;
        $insurance->update(['status' => $insurance->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function insuranceExport()
    {
        $insurances = Insurance::with('insuranceDiseases')->get();
        if (!$insurances) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('insurances.index'));
        }
        return Excel::download(new InsuranceExport, 'insurances-' . time() . '.xlsx');
    }
}
