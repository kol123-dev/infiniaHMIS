<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PathologyParameter
 *
 * @property int $id
 * @property string $parameter_name
 * @property string $reference_range
 * @property int $unit_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PathologyUnit $pathologyUnit
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereParameterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereReferenceRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyParameter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PathologyParameter extends Model
{
    use HasFactory;

    public $table = 'pathology_parameters';

    public $fillable = [
        'parameter_name',
        'reference_range',
        'unit_id',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'parameter_name' => 'string',
        'reference_range' => 'string',
        'unit_id' => 'integer',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'parameter_name' => 'required|unique:pathology_parameters,parameter_name',
        'reference_range' => 'required',
        'unit_id' => 'required',
    ];

    public function pathologyUnit(): BelongsTo
    {
        return $this->belongsTo(PathologyUnit::class, 'unit_id');
    }
}
