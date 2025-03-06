<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddCustomFields extends Model
{
    use HasFactory;

    public $table = 'add_custom_fields';

    public $fillable = [
        'module_name',
        'field_type',
        'field_name',
        'is_required',
        'values',
        'grid',
    ];

    protected $casts = [
        'id' => 'integer',
        'module_name' => 'string',
        'field_type' => 'string',
        'field_name' => 'string',
        'is_required' => 'boolean',
        'values' => 'string',
        'grid' => 'integer'
    ];

    public static $rules = [
        'module_name' => 'required',
        'field_type' => 'required',
        'field_name' => 'required|string',
        'grid' => 'required|numeric|min:6|max:12',
    ];

    const Appointment = 0;
    const IpdPatient = 1;
    const OpdPatient = 2;
    const Patient = 3;

    const MODULE_TYPE_ARR = [
        self::Appointment=> 'Appointment',
        self::IpdPatient => 'IPD Patient',
        self::OpdPatient => 'OPD Patient',
        self::Patient => 'Patient',
    ];

    const FIELD_TYPE_ARR = [
        0 => 'text',
        1 => 'textarea',
        2 => 'toggle',
        3 => 'number',
        4 => 'select',
        5 => 'multiSelect',
        6 => 'date',
        7 => 'date & Time',
    ];
}
