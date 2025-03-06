<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BillItems
 *
 * @version February 13, 2020, 9:51 am UTC
 *
 * @property int $id
 * @property int $medicine_id
 * @property int $bill_id
 * @property int $qty
 * @property float $price
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereMedicineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $item_name
 * @property-read \App\Models\Medicine $medicine
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillItems whereItemName($value)
 */
class BillItems extends Model
{
    public $table = 'bill_items';

    public $fillable = [
        'item_name',
        'bill_id',
        'qty',
        'price',
        'amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'item_name' => 'string',
        'bill_id' => 'integer',
        'qty' => 'integer',
        'price' => 'double',
        'amount' => 'double',
    ];

    public static $rules = [
        'item_name' => 'required|string',
        'qty' => 'required|integer',
        'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }
}
