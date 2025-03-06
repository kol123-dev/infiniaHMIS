<?php

namespace App\Repositories;

use App\Models\BloodDonor;
use App\Models\BloodIssue;

/**
 * Class BloodIssueRepository
 */
class BloodIssueRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'issue_date',
        'doctor_id',
        'donor_id',
        'patient_id',
        'amount',
        'remarks',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return BloodIssue::class;
    }

    public function getBloodGroup($id)
    {
        return BloodDonor::where('id', $id)->pluck('blood_group', 'id');
    }
}
