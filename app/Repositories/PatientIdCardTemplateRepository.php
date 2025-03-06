<?php

namespace App\Repositories;

use App\Models\PatientIdCardTemplate;

class PatientIdCardTemplateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'color',
        'email',
        'phone',
        'dob',
        'blood_group',
        'address',
        'patient_unique_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PatientIdCardTemplate::class;
    }

    public function create($input)
    {
        $input['email'] = !isset($input['email']) ? 0 : 1;
        $input['phone'] = !isset($input['phone']) ? 0 : 1;
        $input['dob'] = !isset($input['dob']) ? 0 : 1;
        $input['blood_group'] = !isset($input['blood_group']) ? 0 : 1;
        $input['address'] = !isset($input['address']) ? 0 : 1;
        $input['patient_unique_id'] = !isset($input['patient_unique_id']) ? 0 : 1;

        PatientIdCardTemplate::create($input);

        return true;
    }

    public function update($id, $input)
    {
        $input['email'] = !isset($input['email']) ? 0 : 1;
        $input['phone'] = !isset($input['phone']) ? 0 : 1;
        $input['dob'] = !isset($input['dob']) ? 0 : 1;
        $input['blood_group'] = !isset($input['blood_group']) ? 0 : 1;
        $input['address'] = !isset($input['address']) ? 0 : 1;
        $input['patient_unique_id'] = !isset($input['patient_unique_id']) ? 0 : 1;

        $PatientIdCardTemplate = PatientIdCardTemplate::find($id);
        $PatientIdCardTemplate->update($input);

        return true;
    }
}
