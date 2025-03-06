<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleCalendarIntegration extends Model
{
    use HasFactory;

    protected $table = 'google_calendar_integrations';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'access_token',
        'meta',
        'last_used_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'access_token' => 'string',
        'meta' => 'string',
        'last_used_at' => 'string',
    ];
}
