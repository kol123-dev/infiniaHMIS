<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Models\InvestigationReport;
use App\Models\Patient;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class InvestigationReportRepository
 *
 * @version February 21, 2020, 9:02 am UTC
 */
class InvestigationReportRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'date',
        'title',
        'description',
        'doctor_id',
        'status',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return InvestigationReport::class;
    }

    public function getPatients()
    {
        $user = Auth::user();
        if ($user->hasRole('Doctor')) {
            $patients = getPatientsList($user->owner_id);
        } else {
            $patients = Patient::with('patientUser')
                ->whereHas('patientUser', function (Builder $query) {
                    $query->where('status', 1);
                })->get()->pluck('patientUser.full_name', 'id')->sort();
        }

        return $patients;
    }

    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }

    public function store($input)
    {
        try {
            $report = InvestigationReport::create($input);

            if (! empty($input['attachment'])) {
                $report->addMedia($input['attachment'])->toMediaCollection(InvestigationReport::COLLECTION_REPORTS,
                    config('app.media_disc'));
            }

            return true;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $id)
    {
        $report = InvestigationReport::find($id);
        try {
            $report->update($input);

            if (! empty($input['attachment'])) {
                $report->clearMediaCollection(InvestigationReport::COLLECTION_REPORTS);
                $report->addMedia($input['attachment'])->toMediaCollection(InvestigationReport::COLLECTION_REPORTS,
                    config('app.media_disc'));
            }
            if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
                removeFile($report, InvestigationReport::COLLECTION_REPORTS);
            }

            return true;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
