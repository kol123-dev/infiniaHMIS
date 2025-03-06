<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\OpdDiagnosis
 *
 * @property int $id
 * @property int $opd_patient_department_id
 * @property string $report_type
 * @property string $report_date
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $opd_diagnosis_document_url
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis query()
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereOpdPatientDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpdDiagnosis whereUpdatedAt($value)
 *
 * @mixin Model
 */
class OpdDiagnosis extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const OPD_DIAGNOSIS_PATH = 'opd_diagnosis';

    public $table = 'opd_diagnoses';

    public $fillable = [
        'opd_patient_department_id',
        'report_type',
        'report_date',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'opd_patient_department_id' => 'integer',
        'report_type' => 'string',
        'report_date' => 'datetime',
        'description' => 'string',
    ];

    public static $rules = [
        'report_type' => 'required',
        'report_date' => 'required',
        'file' => 'nullable|mimes:jpeg,png,pdf,docx,doc',
    ];

    protected $appends = ['opd_diagnosis_document_url'];

    public function getOpdDiagnosisDocumentUrlAttribute()
    {
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }
}
