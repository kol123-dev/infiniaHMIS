<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdConsultantRegisterRequest;
use App\Http\Requests\UpdateIpdConsultantRegisterRequest;
use App\Models\IpdConsultantRegister;
use App\Queries\IpdConsultantRegisterDataTable;
use App\Repositories\IpdConsultantRegisterRepository;
use DataTables;
use Illuminate\Http\Request;

class IpdConsultantRegisterController extends AppBaseController
{
    /** @var IpdConsultantRegisterRepository */
    private $ipdConsultantRegisterRepository;

    public function __construct(IpdConsultantRegisterRepository $ipdConsultantRegisterRepo)
    {
        $this->ipdConsultantRegisterRepository = $ipdConsultantRegisterRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdConsultantRegisterDataTable())->get($request->get('id')))
                ->addColumn('doctorImageUrl', function (IpdConsultantRegister $ipdConsultantRegister) {
                    return $ipdConsultantRegister->doctor->doctorUser->image_url;
                })->make(true);
        }
    }

    public function store(CreateIpdConsultantRegisterRequest $request)
    {
        $input = $request->all();

        for ($i = 0; $i < count($input['doctor_id']); $i++) {
            if ($input['doctor_id'][$i] == 0) {
                return $this->sendError(__('messages.appointment.please_select_doctor'));
            }
        }

        $result = $this->ipdConsultantRegisterRepository->store($input);

        if ($result) {
            return $this->sendSuccess(__('messages.ipd_patient_consultant_register.instruction').' '.__('messages.common.saved_successfully'));
        } else {
            return $this->sendError(__('messages.ipd_patient_consultant_register.applied_date_or_Instruction_date_can_not_be_null'));
        }
    }

    public function edit(IpdConsultantRegister $ipdConsultantRegister)
    {
        return $this->sendResponse($ipdConsultantRegister, 'Consultant Instruction retrieved successfully.');
    }

    public function update(IpdConsultantRegister $ipdConsultantRegister, UpdateIpdConsultantRegisterRequest $request)
    {
        $input = $request->all();
        $this->ipdConsultantRegisterRepository->update($input, $ipdConsultantRegister->id);

        return $this->sendSuccess(__('messages.ipd_patient_consultant_register.instruction').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdConsultantRegister $ipdConsultantRegister)
    {
        $ipdConsultantRegister->delete();

        return $this->sendSuccess(__('messages.ipd_patient_consultant_register.instruction').' '.__('messages.common.deleted_successfully'));
    }
}
