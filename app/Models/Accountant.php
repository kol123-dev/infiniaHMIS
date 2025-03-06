<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * Class Accountant
 *
 * @version February 17, 2020, 5:34 am UTC
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $address
 * @property-read User $user
 *
 * @method static Builder|Accountant newModelQuery()
 * @method static Builder|Accountant newQuery()
 * @method static Builder|Accountant query()
 * @method static Builder|Accountant whereCreatedAt($value)
 * @method static Builder|Accountant whereId($value)
 * @method static Builder|Accountant whereUpdatedAt($value)
 * @method static Builder|Accountant whereUserId($value)
 *
 * @mixin Model
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeePayroll[] $payrolls
 * @property-read int|null $payrolls_count
 * @property int $is_default
 *
 * @method static Builder|Accountant whereIsDefault($value)
 */
class Accountant extends Model
{
    public $table = 'accountants';

    public $fillable = [
        'user_id',
    ];

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const FILTER_STATUS_ARR = [
        0 => 'All',
        1 => 'Active',
        2 => 'Deactive',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public static $rules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email:filter|unique:users,email',
        'password' => 'required|same:password_confirmation|min:6',
        'designation' => 'required|string',
        'qualification' => 'required|string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    public function payrolls(): MorphMany
    {
        return $this->morphMany(EmployeePayroll::class, 'owner');
    }
}
