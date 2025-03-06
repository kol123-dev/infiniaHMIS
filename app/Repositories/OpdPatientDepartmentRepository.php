<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\OpdPatientDepartment;
use App\Models\OpdPrescription;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\Prescription;
use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class OpdPatientDepartmentRepository
 *
 * @version September 8, 2020, 6:42 am UTC
 */
class OpdPatientDepartmentRepository extends BaseRepository
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
        'standard_charge',
        'payment_mode',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return OpdPatientDepartment::class;
    }

    public function getAssociatedData()
    {
        $data['patients'] = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck(
            'patientUser.full_name',
            'id'
        )->sort();
        $data['doctors'] = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck(
            'doctorUser.full_name',
            'id'
        )->sort();
        $data['opdNumber'] = $this->model->generateUniqueOpdNumber();
        $data['paymentMode'] = $this->model::PAYMENT_MODES;

        return $data;
    }

    public function getPatientCases($patientId)
    {
        return PatientCase::where('patient_id', $patientId)->where('status', 1)->pluck('case_id', 'id');
    }

    public function getDoctorsData()
    {
        return Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id');
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

    public function store($input)
    {
        try {
            $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
            $jsonFields = [];

            foreach ($input as $key => $value) {
                if (strpos($key, 'field') === 0) {
                    $jsonFields[$key] = $value;
                }
            }
            $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

            OpdPatientDepartment::create($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function updateOpdPatientDepartment($input, $opdPatientDepartment)
    {
        try {
            $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
            $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
            $jsonFields = [];

            foreach ($input as $key => $value) {
                if (strpos($key, 'field') === 0) {
                    $jsonFields[$key] = $value;
                }
            }
            $input['custom_field'] = !empty($jsonFields) ? $jsonFields : null;

            $opdPatientDepartment->update($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function createNotification($input)
    {
        try {
            $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();
            $doctor = Doctor::with('doctorUser')->where('id', $input['doctor_id'])->first()->doctorUser->fullname;

            if (isset($input['revisit'])) {
                $title = $patient->patientUser->full_name . ' you are visited doctor ' . $doctor . '.';
            } else {
                $title = $patient->patientUser->full_name . ' your OPD record has been created.';
            }

            addNotification([
                Notification::NOTIFICATION_TYPE['OPD Patient'],
                $patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $title,
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getMedicinesCategoriesData(): Collection
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
