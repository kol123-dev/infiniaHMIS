<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PathologyParameterItem
 *
 * @property int $id
 * @property int $pathology_id
 * @property string $patient_result
 * @property int $parameter_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PathologyParameter $pathologyParameter
 * @property-read \App\Models\PathologyTest $pathologyTest
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem wherePathologyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem wherePatientResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameterItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PathologyParameterItem extends Model
{
    use HasFactory;


    public $table = 'pathology_parameter_items';

    public $fillable = [
        'pathology_id',
        'patient_result',
        'parameter_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pathology_id' => 'integer',
        'patient_result' => 'string',
        'parameter_id' => 'integer',
    ];

    public function pathologyTest(): BelongsTo
    {
        return $this->belongsTo(PathologyTest::class, 'pathology_id');
    }

    public function pathologyParameter(): BelongsTo
    {
        return $this->belongsTo(PathologyParameter::class, 'parameter_id');
    }
}
