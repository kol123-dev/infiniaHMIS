<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PathologyUnit
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PathologyUnit extends Model
{
    use HasFactory;

    public $table = 'pathology_units';

    public $fillable = [
        'name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:pathology_units,name',
    ];
}
