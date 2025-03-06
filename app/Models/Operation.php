<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Operation extends Model
{
    use HasFactory;

    public $table = 'operations';

    public $fillable = ['operation_category_id', 'name'];

    protected $casts = [
        'id' => 'integer',
        'operation_category_id' => 'integer',
        'name' => 'string',
    ];

    public static $rules = [
        'operation_category_id' => 'required',
        'name' => 'required|unique:operations,name',
    ];

    public function operation_category(): BelongsTo
    {
        return $this->belongsTo(OperationCategory::class);
    }
}
