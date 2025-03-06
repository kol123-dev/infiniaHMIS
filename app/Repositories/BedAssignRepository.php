<?php

namespace App\Repositories;

use App\Models\Bed;
use App\Models\BedAssign;
use App\Models\Doctor;
use App\Models\IpdPatientDepartment;
use App\Models\Notification;
use App\Models\Nurse;
use App\Models\PatientCase;
use App\Models\User;
use Arr;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class BedAssignRepository
 *
 * @version February 18, 2020, 6:49 am UTC
 */
class BedAssignRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'case_id',
        'assign_date',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return BedAssign::class;
    }

    public function getBeds()
    {
        $beds = Bed::where('is_available', 1)->pluck('name', 'id')->toArray();
        natcasesort($beds);

        return $beds;
    }

    public function getCases()
    {
        $user = \Auth::user();

        if ($user->hasRole('Doctor')) {
            $cases = PatientCase::whereDoesntHave('bedAssign')->with('patient.patientUser')->where('doctor_id', '=',
                $user->owner_id)->where('status', '=', 1)->get();
        } else {
            $cases = PatientCase::whereDoesntHave('bedAssign')->with('patient.patientUser')->where('status', '=', 1)->get();
        }

        $result = [];
        foreach ($cases as $case) {
            $result[$case->case_id] = $case->case_id.'  '.$case->patient->patientUser->full_name;
        }

        ksort($result);

        return $result;
    }

    public function getIpdPatients($caseId)
    {
        $patientCase = PatientCase::where('case_id', $caseId)->value('id');

        return IpdPatientDepartment::whereCaseId($patientCase)->pluck('ipd_number', 'id');
    }

    public function store($input)
    {
        try {
            $caseId = $input['case_id'];
            $patientId = PatientCase::whereCaseId($caseId)->first()->patient_id;
            $input['patient_id'] = $patientId;
            BedAssign::create($input);

            $bed = Bed::find($input['bed_id']);
            $bed->update(['is_available' => 0]);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $bedAssign)
    {
        try {
            $oldBed = BedAssign::with('bed','ipdPatient')->where('case_id',$input['case_id'])->latest()->first();

            if($oldBed){
                $oldBed->ipdPatient()->update(['bed_id' => $input['bed_id']]);
                $oldBed->bed()->update(['is_available' => 1]);
            }

            $patientAdmissionRepo = app(PatientAdmissionRepository::class);
            $input['discharge_date'] = (! empty($input['discharge_date'])) ? $input['discharge_date'] : null;
            $bedAssign->update($input);

            if (isset($bedId)) {
                $patientAdmissionRepo->setBedUnAvailable($bedId);
            } elseif (isset($input['bed_id'])) {
                $patientAdmissionRepo->setBedUnAvailable($input['bed_id']);
            } else {
                $patientAdmissionRepo->setBedAvailable($input['bed_id']);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getPatientBeds($bedAssign)
    {
        $beds = Bed::where('is_available', 1)->orWhere('id', $bedAssign->bed_id)->where('is_available',
            0)->pluck('name', 'id')->toArray();
        natcasesort($beds);

        return $beds;
    }

    public function getPatientCases($bedAssign)
    {
        $cases = PatientCase::whereDoesntHave('bedAssign')->orWhere('case_id', $bedAssign->case_id)->get();

        $result = [];
        foreach ($cases as $case) {
            $result[$case->case_id] = $case->case_id.'  '.$case->patient->patientUser->full_name;
        }
        ksort($result);

        return $result;
    }

    public function createNotification($input)
    {
        try {
            $patient = PatientCase::whereCaseId($input['case_id'])->first()->patient->patientUser->full_name;
            $ownerType = [Doctor::class, Nurse::class];
            $userIds = User::whereIn('owner_type', $ownerType)->pluck('owner_type', 'id')->toArray();
            $adminUser = User::role('Admin')->first();
            $allUsers = $userIds + [$adminUser->id => Notification::NOTIFICATION_FOR[Notification::ADMIN]];
            $users = getAllNotificationUser($allUsers);

            foreach ($users as $id => $ownerType) {
                addNotification([
                    Notification::NOTIFICATION_TYPE['Bed Assign'],
                    $id,
                    Notification::NOTIFICATION_FOR[User::getOwnerType($ownerType)],
                    $patient.' has bed assigned.',
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
