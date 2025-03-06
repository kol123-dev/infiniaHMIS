<?php

namespace App\Repositories;

use App\Models\IpdPatientDepartment;
use App\Models\IpdPrescription;
use App\Models\IpdPrescriptionItem;
use App\Models\Medicine;
use App\Models\MedicineBill;
use App\Models\Notification;
use App\Models\SaleMedicine;
use Arr;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IpdPrescriptionRepository
 *
 * @version September 10, 2020, 11:42 am UTC
 */
class IpdPrescriptionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ipd_patient_department_id',
        'created_at',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return IpdPrescription::class;
    }

    public function getMedicines($medicineCategoryId)
    {
        return Medicine::where('category_id', $medicineCategoryId)->pluck('name', 'id');
    }

    public function store($input)
    {
        try {
            $ipdPrescriptionArr = Arr::only($input, $this->model->getFillable());
            $ipdPrescription = $this->model->create($ipdPrescriptionArr);
            $ipdDepartment = IpdPatientDepartment::with('patient', 'doctor')->whereId($input['ipd_patient_department_id'])->first();
            $amount = 0;
            $qty = 0;
            $medicineBill = MedicineBill::create([
                'bill_number' => 'BIL'.generateUniqueBillNumber(),
                'patient_id' => $ipdDepartment->patient->id,
                'doctor_id' => $ipdDepartment->doctor->id,
                'model_type' => \App\Models\IpdPrescription::class,
                'model_id' => $ipdPrescription->id,
                'bill_date' => Carbon::now(),
                'payment_status' => MedicineBill::UNPAID,
            ]);

            foreach ($input['category_id'] as $key => $value) {
                $ipdPrescriptionItem = [
                    'ipd_prescription_id' => $ipdPrescription->id,
                    'category_id' => $input['category_id'][$key],
                    'medicine_id' => $input['medicine_id'][$key],
                    'dosage' => $input['dosage'][$key],
                    'day' => $input['day'][$key],
                    'time' => $input['time'][$key],
                    'dose_interval' => $input['dose_interval'][$key],
                    'instruction' => $input['instruction'][$key],
                ];
                IpdPrescriptionItem::create($ipdPrescriptionItem);

                $medicine = Medicine::find($input['medicine_id'][$key]);
                $amount += $input['day'][$key] * $input['dose_interval'][$key] * $medicine->selling_price;
                $qty = $input['day'][$key] * $input['dose_interval'][$key];
                $saleMedicineArray = [
                    'medicine_bill_id' => $medicineBill->id,
                    'medicine_id' => $medicine->id,
                    'sale_quantity' => $qty,
                    'sale_price' => $medicine->selling_price,
                    'tax' => 0,
                ];
                SaleMedicine::create($saleMedicineArray);
            }
            $medicineBill->update([
                'net_amount' => $amount,
                'amount' => $amount,
            ]);

        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function getIpdPrescriptionData($ipdPrescription)
    {
        $data['ipdPrescription'] = $ipdPrescription->toArray();
        $data['ipdPrescription']['medicine'] = $ipdPrescription->ipdPrescriptionItems->toArray();
        $data['ipdPrescriptionItems'] = $ipdPrescription->ipdPrescriptionItems->toArray();
        $data['medicines'] = Medicine::pluck('name', 'id');
        $data['medicines_qty'] = Medicine::pluck('available_quantity', 'id');

        return $data;
    }

    public function updateIpdPrescriptionItems($input, $ipdPrescription)
    {
        try {
            $medicineBill = MedicineBill::whereModelType(\App\Models\IpdPrescription::class)->whereModelId($ipdPrescription->id)->first();
            $medicineBill->saleMedicine()->delete();
            $ipdPrescriptionArr = Arr::only($input, $this->model->getFillable());
            $ipdPrescription->update($ipdPrescriptionArr);
            $ipdPrescription->ipdPrescriptionItems()->delete();
            $ipdDepartment = IpdPatientDepartment::with('patient', 'doctor')->whereId($input['ipd_patient_department_id'])->first();
            $amount = 0;
            $qty = 0;

            foreach ($input['category_id'] as $key => $value) {
                $ipdPrescriptionItem = [
                    'ipd_prescription_id' => $ipdPrescription->id,
                    'category_id' => $input['category_id'][$key],
                    'medicine_id' => $input['medicine_id'][$key],
                    'dosage' => $input['dosage'][$key],
                    'day' => $input['day'][$key],
                    'time' => $input['time'][$key],
                    'dose_interval' => $input['dose_interval'][$key],
                    'instruction' => $input['instruction'][$key],
                ];
                IpdPrescriptionItem::create($ipdPrescriptionItem);

                $medicine = Medicine::find($input['medicine_id'][$key]);
                $amount += $input['day'][$key] * $input['dose_interval'][$key] * $medicine->selling_price;
                $qty = $input['day'][$key] * $input['dose_interval'][$key];
                $saleMedicineArray = [
                    'medicine_bill_id' => $medicineBill->id,
                    'medicine_id' => $medicine->id,
                    'sale_quantity' => $qty,
                    'sale_price' => $medicine->selling_price,
                    'tax' => 0,
                ];
                SaleMedicine::create($saleMedicineArray);
            }
            $medicineBill->update([
                'net_amount' => $amount,
            ]);

        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function createNotification($input)
    {
        try {
            $patient = IpdPatientDepartment::with('patient.patientUser')->where('id',
                $input['ipd_patient_department_id'])->first();
            $doctor = IpdPatientDepartment::with('doctor.doctorUser')->where('id',
                $input['ipd_patient_department_id'])->first();
            $userIds = [
                $doctor->doctor->user_id => Notification::NOTIFICATION_FOR[Notification::DOCTOR],
                $patient->patient->user_id => Notification::NOTIFICATION_FOR[Notification::PATIENT],
            ];

            foreach ($userIds as $key => $notification) {
                if ($notification == Notification::NOTIFICATION_FOR[Notification::PATIENT]) {
                    $title = $patient->patient->patientUser->full_name.' your IPD prescription has been created.';
                } else {
                    $title = $patient->patient->patientUser->full_name.' IPD prescription has been created.';
                }
                addNotification([
                    Notification::NOTIFICATION_TYPE['IPD Prescription'],
                    $key,
                    $notification,
                    $title,
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
