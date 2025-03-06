<?php

namespace App\Repositories;

use App;
use App\Models\Bed;
use App\Models\BedAssign;
use App\Models\BedType;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\IpdCharge;
use App\Models\IpdConsultantRegister;
use App\Models\IpdDiagnosis;
use App\Models\IpdOperation;
use App\Models\IpdPatientDepartment;
use App\Models\IpdPayment;
use App\Models\IpdPrescription;
use App\Models\IpdTimeline;
use App\Models\Notification;
use App\Models\OperationCategory;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\Prescription;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IpdPatientDepartmentRepository
 *
 * @version September 8, 2020, 6:42 am UTC
 */
class IpdPatientDepartmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_number',
        'height',
        'weight',
        'bp',
        'symptoms',
        'notes',
        'admission_date',
        'case_id',
        'is_old_patient',
        'doctor_id',
        'bed_group_id',
        'bed_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return IpdPatientDepartment::class;
    }

    public function getAssociatedData()
    {
        $data['patients'] = Patient::with('patientUser')->get()->where(
            'patientUser.status',
            '=',
            1
        )->pluck(
            'patientUser.full_name',
            'id'
        )->sort();
        $data['doctors'] = Doctor::with('doctorUser')->get()->where(
            'doctorUser.status',
            '=',
            1
        )->pluck(
            'doctorUser.full_name',
            'id'
        )->sort();
        $data['bedTypes'] = BedType::pluck('title', 'id')->toArray();
        natcasesort($data['bedTypes']);
        $data['ipdNumber'] = $this->model->generateUniqueIpdNumber();

        return $data;
    }

    public function getPatientCases($patientId)
    {
        return PatientCase::where('patient_id', $patientId)->where('status', 1)->get()->pluck('case_id', 'id');
    }

    public function getPatientBeds($bedTypeId, $isEdit, $bedId, $ipdPatientBedTypeId)
    {
        $beds = null;
        if (! $isEdit) {
            $beds = Bed::orderBy('name')->where('bed_type', $bedTypeId)->where('is_available', 1)->pluck('name', 'id');
        } else {
            $beds = Bed::orderBy('name')->where('bed_type', $bedTypeId)->where('is_available', 1);
            if ($bedTypeId == $ipdPatientBedTypeId) {
                $beds->orWhere('id', $bedId);
            }
            $beds = $beds->pluck('name', 'id');
        }

        return $beds;
    }

    public function getDoctorsData()
    {
        return Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck(
            'doctorUser.full_name',
            'id'
        );
    }

    public function getPatientsData()
    {
        return Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck(
            'patientUser.full_name',
            'id'
        );
    }

    public function getDoctorsList()
    {
        $result = Doctor::with('doctorUser')->get()
            ->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->toArray();

        $doctors = [];
        foreach ($result as $key => $item) {
            $doctors[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doctors;
    }

    public function getMedicinesCategoriesData()
    {
        return Category::where('is_active', '=', 1)->pluck('name', 'id');
    }

    public function getMedicineCategoriesList()
    {
        $result = Category::where('is_active', '=', 1)->pluck('name', 'id')->toArray();

        $medicineCategories = [];
        foreach ($result as $key => $item) {
            $medicineCategories[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $medicineCategories;
    }

    public function store($input)
    {
        $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
        $input['discharge'] = false;

        $jsonFields = [];

        foreach ($input as $key => $value) {
            if (strpos($key, 'field') === 0) {
                $jsonFields[$key] = $value;
            }
        }

        $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

        $ipdPatientDepartment = IpdPatientDepartment::create($input);

        $bedAssignData = [
            'bed_id' => $input['bed_id'],
            'patient_id' => $input['patient_id'],
            'assign_date' => $input['admission_date'],
            'case_id' => $ipdPatientDepartment->patientCase ? $ipdPatientDepartment->patientCase->case_id : '',
            'ipd_patient_department_id' => $ipdPatientDepartment->id,
            'status' => true,
        ];

        $bedAssign = App::make(BedAssignRepository::class);
        $bedAssign->store($bedAssignData);

        return true;
    }

    public function updateIpdPatientDepartment($input, $ipdPatientDepartment)
    {
        try {
            $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
            $bedId = $ipdPatientDepartment->bed_id;
            $jsonFields = [];

            foreach ($input as $key => $value) {
                if (strpos($key, 'field') === 0) {
                    $jsonFields[$key] = $value;
                }
            }
            $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

            if (isset($input['discharge']) && $input['discharge']  == 1) {
                $ipdPatientDepartment->update([
                    'patient_id' => $ipdPatientDepartment->patient_id,
                    'ipd_number' => $input['ipd_number'],
                    'height' => $input['height'],
                    'weight' => $input['weight'],
                    'bp' => $input['bp'],
                    'symptoms' => $input['symptoms'],
                    'notes' => $input['notes'],
                    'admission_date' => $input['admission_date'],
                    'case_id' => $ipdPatientDepartment->case_id,
                    'is_old_patient' => $input['is_old_patient'],
                    'doctor_id' => $input['doctor_id'],
                    'bed_type_id' => $ipdPatientDepartment->bed_type_id,
                    'bed_id' => $ipdPatientDepartment->bed_id,
                    'discharge' => $ipdPatientDepartment->discharge,
                    'custom_field' => $jsonFields,
                ]);

                return true;
            } else {
                $ipdPatientDepartment = $this->update($input, $ipdPatientDepartment->id);
            }

            $bedAssignData = [
                'bed_id' => $input['bed_id'],
                'patient_id' => $input['patient_id'],
                'case_id' => $ipdPatientDepartment->patientCase ? $ipdPatientDepartment->patientCase->case_id : '',
                'assign_date' => $input['admission_date'],
                'status' => true,
            ];

            $bedAssignUpdate = BedAssign::whereBedId($bedId)->latest()->first();

            $bedAssign = App::make(BedAssignRepository::class);

            if (empty($bedAssignUpdate)) {
                $bedAssigns = BedAssign::create($bedAssignData);
                BedAssign::where('id', $bedAssigns->id)->update(['ipd_patient_department_id' => $ipdPatientDepartment->id]);
            } else {
                $bedAssign->update($bedAssignData, $bedAssignUpdate);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function deleteIpdPatientDepartment($ipdPatientDepartment)
    {
        $ipdPatientDepartment->bed->update(['is_available' => 1]);

        if ($ipdPatientDepartment->bedAssign) {
            BedAssign::where('id', $ipdPatientDepartment->bedAssign->id)->delete();
        }

        $ipdPatientDepartment->delete();

        return true;
    }

    public function getSyncListForCreate()
    {
        $data['setting'] = Setting::pluck('value', 'key');

        return $data;
    }

    public function createNotification($input)
    {
        try {
            $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['IPD Patient'],
                $patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patientUser->full_name . ' your IPD record has been created.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getConsultantRegister($id)
    {
        $consultantRegister = IpdConsultantRegister::whereHas('doctor.doctorUser')->with('doctor.doctorUser')->where('ipd_patient_department_id', $id)->latest()->take(5)->get();

        return $consultantRegister;
    }

    public function getConsultantDoctor($id)
    {
        $consultantRegister = IpdConsultantRegister::whereHas('doctor.doctorUser')->with('doctor.doctorUser')->where('ipd_patient_department_id', $id)->groupBy('doctor_id')->latest()->take(5)->get();

        return $consultantRegister;
    }

    public function getIPDOperation($id)
    {
        $ipdOperation = IpdOperation::where('ipd_patient_department_id', $id)->latest()->take(5)->get();

        return $ipdOperation;
    }

    public function getIPDTimeline($id)
    {
        if (Auth::user()->hasRole('Admin')) {
            return IpdTimeline::where('ipd_patient_department_id', $id)->latest()->take(2)->get();
        }

        return IpdTimeline::where('ipd_patient_department_id', $id)->latest()->take(2)->visible()->get();
    }

    public function getIPDPrescription($id)
    {
        return IpdPrescription::with('patient')->where('ipd_patient_department_id', $id)->latest()->take(5)->get();
    }

    public function getIPDCharges($id)
    {
        return IpdCharge::with(['chargecategory', 'charge'])->where('ipd_patient_department_id', $id)->latest()->take(5)->get();
    }

    public function getIPDPayment($id)
    {
        return IpdPayment::whereIpdPatientDepartmentId($id)->latest()->take(5)->get();
    }

    public function getIPDDiagnosis($id)
    {
        return IpdDiagnosis::whereIpdPatientDepartmentId($id)->latest()->take(5)->get();
    }

    public function getOperationCategoryList()
    {
        return OperationCategory::get()->pluck('name', 'id')->toArray();
    }

    public function getDoseDurationList()
    {
        $result = Prescription::DOSE_DURATION;

        $doseDuration = [];
        foreach ($result as $key => $item) {
            $doseDuration[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doseDuration;
    }

    public function getDoseIntervalList()
    {
        $result = Prescription::DOSE_INTERVAL;

        $doseInterval = [];
        foreach ($result as $key => $item) {
            $doseInterval[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doseInterval;
    }

    public function getMealList()
    {
        $result = Prescription::MEAL_ARR;

        $meal = [];
        foreach ($result as $key => $item) {
            $meal[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $meal;
    }
}
