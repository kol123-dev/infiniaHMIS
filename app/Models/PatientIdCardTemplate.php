<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientIdCardTemplate extends Model
{
    use HasFactory;

    public $table = 'patient_id_card_templates';

    public $fillable = [
        'name',
        'color',
        'email',
        'phone',
        'dob',
        'blood_group',
        'address',
        'patient_unique_id',
    ];

    protected $casts = [
        'name' => 'string',
        'color' => 'string',
        'email' => 'boolean',
        'phone' => 'boolean',
        'dob' => 'boolean',
        'blood_group' => 'boolean',
        'address' => 'boolean',
        'patient_unique_id' => 'boolean',
    ];

    public static $rules = [
        'name' => 'required|unique:patient_id_card_templates',
        'color' => 'required',
    ];

    public function patient(): HasMany
    {
        return $this->hasMany(Patient::class, 'template_id');
    }
}
