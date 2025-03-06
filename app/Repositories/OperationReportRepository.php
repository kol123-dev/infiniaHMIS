<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Models\OperationReport;
use App\Models\PatientCase;
use Auth;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class OperationReportRepository
 *
 * @version February 18, 2020, 6:02 am UTC
 */
class OperationReportRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_name',
        'case_number',
        'doctor_id',
        'date',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return OperationReport::class;
    }

    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }

    public function getCases()
    {
        $user = Auth::user();
        if ($user->hasRole('Doctor')) {
            $cases = PatientCase::with('patient.patientUser')->where('doctor_id', '=', $user->owner_id)->get()->where('status',
                '=', 1);
        } else {
            $cases = PatientCase::with('patient.patientUser')->get()->where('status', '=', 1)->sort();
        }

        $result = [];
        foreach ($cases as $case) {
            $result[$case->case_id] = $case->case_id.'  '.$case->patient->patientUser->full_name;
        }

        return $result;
    }

    public function store($input)
    {
        try {
            $caseId = $input['case_id'];
            $input['date'] = Carbon::parse($input['date'])->format('Y-m-d H:i:s');
            $patientId = PatientCase::whereCaseId($caseId)->first()->patient_id;
            $input['patient_id'] = $patientId;
            $operationReport = OperationReport::create($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $operationReport)
    {
        try {
            $caseId = $input['case_id'];
            $input['date'] = Carbon::parse($input['date'])->format('Y-m-d H:i:s');
            $patientId = PatientCase::whereCaseId($caseId)->first()->patient_id;
            $input['patient_id'] = $patientId;
            $operationReport->update($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
