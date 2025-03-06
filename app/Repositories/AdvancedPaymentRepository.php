<?php

namespace App\Repositories;

use App\Models\AdvancedPayment;
use App\Models\Notification;
use App\Models\Patient;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class AdvancedPaymentRepository
 *
 * @version March 2, 2020, 4:38 am UTC
 */
class AdvancedPaymentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'receipt_no',
        'amount',
        'date',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return AdvancedPayment::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function createNotification($input)
    {
        try {
            $patient = AdvancedPayment::with('patient.patientUser')->where('patient_id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['Advance Payment'],
                $patient->patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patient->patientUser->full_name.' your advance payment receive successfully.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
