<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoogleCalendarList extends Model
{
    use HasFactory;

    protected $table = 'google_calendar_lists';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'calendar_name',
        'google_calendar_id',
        'meta',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'calendar_name' => 'string',
        'google_calendar_id' => 'string',
        'meta' => 'string',
    ];

    public function eventGoogleCalendar(): BelongsTo
    {
        return $this->belongsTo(EventGoogleCalendar::class);
    }
}
