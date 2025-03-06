<?php

namespace App\Repositories;

use App\Models\Medicine;
use App\Models\MedicineBill;
use App\Models\Notification;
use App\Models\OpdPatientDepartment;
use App\Models\OpdPrescription;
use App\Models\OpdPrescriptionItem;
use App\Models\SaleMedicine;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class OpdDiagnosisRepository
 *
 */
class OpdPresciptionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'opd_patient_department_id',
        'created_at',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return OpdPrescription::class;
    }

    public function getMedicines($medicineCategoryId)
    {
        return Medicine::where('category_id', $medicineCategoryId)->pluck('name', 'id');
    }

    public function store($input)
    {
        try{
            $opdDepartment = OpdPatientDepartment::with(['patient','doctor'])->whereId($input['opd_patient_department_id'])->first();
            $amount = 0;
            $qty = 0;

            $opdPrescription = OpdPrescription::create([
                'opd_patient_department_id' => $input['opd_patient_department_id'],
                'header_note' => $input['header_note'],
                'footer_note' => $input['footer_note']
            ]);

            $medicineBill = MedicineBill::create([
                'bill_number' => 'BIL'.generateUniqueBillNumber(),
                'patient_id' => $opdDepartment->patient->id,
                'doctor_id' => $opdDepartment->doctor->id,
                'model_type' => \App\Models\OpdPrescription::class,
                'model_id' => $opdPrescription->id,
                'bill_date' => Carbon::now(),
                'payment_status' => MedicineBill::UNPAID,
            ]);

            foreach ($input['category_id'] as $key => $value) {
                $opdPrescriptionItem = [
                    'opd_prescription_id' => $opdPrescription->id,
                    'category_id' => $input['category_id'][$key],
                    'medicine_id' => $input['medicine_id'][$key],
                    'dosage' => $input['dosage'][$key],
                    'day' => $input['day'][$key],
                    'time' => $input['time'][$key],
                    'dose_interval' => $input['dose_interval'][$key],
                    'instruction' => $input['instruction'][$key],
                ];
                OpdPrescriptionItem::create($opdPrescriptionItem);

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

        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return true;
    }

    public function createNotification($input)
    {
        try {
            $patient = OpdPatientDepartment::with('patient.patientUser')->where('id',
                $input['opd_patient_department_id'])->first();
            $doctor = OpdPatientDepartment::with('doctor.doctorUser')->where('id',
                $input['opd_patient_department_id'])->first();
            $userIds = [
                $doctor->doctor->user_id => Notification::NOTIFICATION_FOR[Notification::DOCTOR],
                $patient->patient->user_id => Notification::NOTIFICATION_FOR[Notification::PATIENT],
            ];

            foreach ($userIds as $key => $notification) {
                if ($notification == Notification::NOTIFICATION_FOR[Notification::PATIENT]) {
                    $title = $patient->patient->patientUser->full_name.' your OPD prescription has been created.';
                } else {
                    $title = $patient->patient->patientUser->full_name.' OPD prescription has been created.';
                }
                addNotification([
                    Notification::NOTIFICATION_TYPE['OPD Prescription'],
                    $key,
                    $notification,
                    $title,
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getOpdPrescriptionData($opdPrescription)
    {
        $data['opdPrescription'] = $opdPrescription->toArray();
        $data['opdPrescription']['medicine'] = $opdPrescription->opdPrescriptionItems->toArray();
        $data['opdPrescriptionItems'] = $opdPrescription->opdPrescriptionItems->toArray();
        $data['medicines'] = Medicine::pluck('name', 'id');
        $data['medicines_qty'] = Medicine::pluck('available_quantity', 'id');

        return $data;
    }

    public function updateopdPrescriptionItems($input,$opdPrescription)
    {
        try {
            $medicineBill = MedicineBill::whereModelType(\App\Models\OpdPrescription::class)->whereModelId($opdPrescription->id)->first();
            $medicineBill->saleMedicine()->delete();

            $opdPrescriptionArr = Arr::only($input, $this->model->getFillable());
            $opdPrescription->update($opdPrescriptionArr);
            $opdPrescription->OpdPrescriptionItems()->delete();

            $opdDepartment = OpdPatientDepartment::with('patient', 'doctor')->whereId($input['opd_patient_department_id'])->first();
            $amount = 0;
            $qty = 0;
            foreach ($input['category_id'] as $key => $value) {
                $opdPrescriptionItem = [
                    'opd_prescription_id' => $opdPrescription->id,
                    'category_id' => $input['category_id'][$key],
                    'medicine_id' => $input['medicine_id'][$key],
                    'dosage' => $input['dosage'][$key],
                    'day' => $input['day'][$key],
                    'time' => $input['time'][$key],
                    'dose_interval' => $input['dose_interval'][$key],
                    'instruction' => $input['instruction'][$key],
                ];

                OpdPrescriptionItem::create($opdPrescriptionItem);

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
}
