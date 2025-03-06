<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGoogleEventSchedule extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'user_google_event_schedules';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'google_live_consultation_id',
        'google_calendar_id',
        'google_event_id',
        'google_meet_link',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'google_live_consultation_id' => 'string',
        'google_calendar_id' => 'string',
        'google_event_id' => 'string',
        'google_meet_link' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
