<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIPDOperationRequest;
use App\Models\IpdOperation;
use Str;

class IpdOperationController extends AppBaseController
{
    public function store(CreateIPDOperationRequest $request)
    {
        $operationRefNo = strtoupper(Str::random(8));

        while (true) {
            $isExist = IpdOperation::where('ref_no', $operationRefNo)->exists();
            if ($isExist) {
                $operationRefNo = strtoupper(Str::random(8));
            }
            break;
        }

        $input = [
            'ref_no' => $operationRefNo,
            'operation_category_id' => $request->operation_category_id,
            'operation_id' => $request->operation_id,
            'ipd_patient_department_id' => $request->ipd_patient_department_id,
            'operation_date' => $request->operation_date,
            'doctor_id' => $request->doctor_id,
            'assistant_consultant_1' => $request->assistant_consultant_1,
            'assistant_consultant_2' => $request->assistant_consultant_2,
            'anesthetist' => $request->anesthetist,
            'anesthesia_type' => $request->anesthesia_type,
            'ot_technician' => $request->ot_technician,
            'ot_assistant' => $request->ot_assistant,
            'remark' => $request->remark,
            'result' => $request->result,
        ];

        IpdOperation::create($input);

        return $this->sendSuccess(__('messages.ipd_operation.ipd_operation_created_successfully'));
    }

    public function edit($id)
    {
        $data = IpdOperation::where('id', $id)->first();

        return $this->sendResponse($data, 'IPD operation retrieved successfully.');
    }

    public function update($id, CreateIPDOperationRequest $request)
    {
        $ipdOperation = IpdOperation::where('id', $id)->first();
        $ipdOperation->update($request->all());

        return $this->sendSuccess(__('messages.ipd_operation.ipd_operation').' '.__('messages.common.updated_successfully'));
    }

    public function delete($id)
    {
        IpdOperation::where('id', $id)->delete();

        return $this->sendSuccess(__('messages.ipd_operation.ipd_operation').' '.__('messages.common.deleted_successfully'));
    }
}
