<?php

namespace App\Listeners;

use App;

use App\Http\Controllers\GoogleMeetLiveConsultationController;
use App\Models\EventGoogleCalendar;
use App\Models\GoogleCalendarIntegration;
use App\Models\LiveConsultation;
use App\Models\UserGoogleEventSchedule;

class HandleCreateGoogleEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $liveConsultation): void
    {
        $liveConsultationID = $liveConsultation->liveConsultationID;

        $this->createGoogleEvent($liveConsultationID);
    }

    public function createGoogleEvent($liveConsultationID): bool
    {
        $liveConsultation = LiveConsultation::with(['user'])->find($liveConsultationID);
        $googleCalendarConnected = GoogleCalendarIntegration::where('user_id',$liveConsultation->user->id)
            ->exists();

        if ($googleCalendarConnected) {
            /** @var GoogleMeetLiveConsultationController $repo */
            $repo = App::make(GoogleMeetLiveConsultationController::class);

            $calendarLists = EventGoogleCalendar::where('user_id',$liveConsultation->user->id)
                ->pluck('google_calendar_id')
                ->toArray();

            $meta['name'] = 'Live Consultation Title : '.$liveConsultation->consultation_title;
            $meta['description'] = 'Live Consultation Description : '.$liveConsultation->description;
            $meta['lists'] = $calendarLists;

            $accessToken = $repo->getAccessToken($liveConsultation->user->id);
            $results = $repo->store($liveConsultation, $accessToken, $meta);

            foreach ($results as $result) {
                UserGoogleEventSchedule::create([
                    'user_id' => $liveConsultation->user->id,
                    'google_live_consultation_id' => $liveConsultation->id,
                    'google_calendar_id' => $result['google_calendar_id'],
                    'google_event_id' => $result['id'],
                    'google_meet_link' => $result['google_meet_link'],
                ]);
            }
        }

        return true;
    }
}
