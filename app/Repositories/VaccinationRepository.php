<?php

namespace App\Repositories;

use App\Models\Vaccination;

/**
 * Class VaccinationRepository
 *
 * @version March 31, 2020, 12:22 pm UTC
 */
class VaccinationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'manufactured_by',
        'brand',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Vaccination::class;
    }
}
