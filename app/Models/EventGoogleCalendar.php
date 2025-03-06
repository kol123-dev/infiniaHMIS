<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventGoogleCalendar extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'event_google_calendars';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'google_calendar_list_id',
        'google_calendar_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'google_calendar_list_id' => 'integer',
        'google_calendar_id' => 'string',
    ];

    public function googleCalendarList(): BelongsTo
    {
        return $this->BelongsTo(GoogleCalendarList::class);
    }
}
