<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\BirthReport;
use App\Models\Doctor;

class BirthReportAPIController extends AppBaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $doctorId = Doctor::where('user_id', getLoggedInUserId())->first();
        $birthReports = BirthReport::with('patient', 'doctor', 'caseFromBirthReport')->where('doctor_id',
            $doctorId->id)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($birthReports as $birthReport) {
            $data[] = $birthReport->prepareBirthReport();
        }

        return $this->sendResponse($data, 'BirthReports Retrieved Successfully');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $birthReport = BirthReport::with('patient', 'doctor', 'caseFromBirthReport')->where('id',
            $id)->where('doctor_id', getLoggedInUser()->id)->first();

        /** @var BirthReport $birthReport */

        return $this->sendResponse($birthReport->prepareBirthReport(), 'BirthReport Retrieved Successfully');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $birthReport = BirthReport::with('patient', 'doctor', 'caseFromBirthReport')->where('id',
            $id)->where('doctor_id', getLoggedInUser()->owner_id)->first();
        if (! $birthReport || $birthReport->doctor_id != getLoggedInUser()->owner_id) {
            return $this->sendError(__('messages.birth_report.birth_report').' '.__('messages.common.not_found'));
        } else {
            $birthReport->delete();

            return $this->sendSuccess(__('messages.birth_report.birth_report').' '.__('messages.common.deleted_successfully'));
        }
    }
}
