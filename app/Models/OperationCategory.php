<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCategory extends Model
{
    use HasFactory;

    public $table = 'operation_categories';

    public $fillable = ['name'];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public static $rules = [
        'name' => 'required|unique:operation_categories,name',
    ];
}
