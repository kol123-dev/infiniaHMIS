<?php

namespace App\Repositories;

use App\Models\LiveConsultation;
use App\Models\LiveMeeting;
use App\Models\UserZoomCredential;
use App\Models\ZoomOAuth;
use Flash;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ZoomRepository
{
    const MEETING_TYPE_INSTANT = 1;

    const MEETING_TYPE_SCHEDULE = 2;

    const MEETING_TYPE_RECURRING = 3;

    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function connectWithZoom($code)
    {
        $userZoomCredential = UserZoomCredential::where('user_id', getLoggedInUserId())->first();

        if (isset($userZoomCredential)) {
            $clientID = $userZoomCredential->zoom_api_key;
            $secret = $userZoomCredential->zoom_api_secret;
        } else {
            return Flash::error(__('messages.live_consultation.add_credentials_for_zoom_meeting'));
        }

        $client = new Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic '.base64_encode($clientID.':'.$secret),
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('app.zoom_callback'),
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);

        $exist = ZoomOAuth::where('user_id', Auth::id())->first();

        if (! $exist) {
            ZoomOAuth::create([
                'user_id' => Auth::id(),
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
        } else {
            $exist->update([
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
        }

        return true;
    }

    public function updateZoomMeeting($data, $liveConsultation)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);

        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            $response = $client->request('PATCH', 'v2/meetings/'.$liveConsultation->meeting_id, [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
                'json' => [
                    'topic' => $data['consultation_title'],
                    'type' => 2,
                    'start_time' => $this->toZoomTimeFormat($data['consultation_date']),
                    'duration' => $data['consultation_duration_minutes'],
                    'agenda' => (! empty($data['description'])) ? $data['description'] : null,
                    'password' => '123456',
                    'settings' => [
                        'host_video' => ($data['host_video'] == LiveConsultation::HOST_ENABLE) ? true : false,
                        'participant_video' => ($data['participant_video'] == LiveConsultation::CLIENT_ENABLE) ? true : false,
                        'waiting_room' => true,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody());

            return (array) $data;
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.invalid_access_token'));
            }
            if ($e->getCode() == 0) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.connect_with_zoom'));
            }
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createZoomMeeting($data)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            if(empty($zoomOAuth)){
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.connect_with_zoom'));
            }

            $response = $client->request('POST', '/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
                'json' => [
                    'topic' => $data['consultation_title'],
                    'type' => 2,
                    'start_time' => $this->toZoomTimeFormat($data['consultation_date']),
                    'duration' => $data['consultation_duration_minutes'],
                    'agenda' => (! empty($data['description'])) ? $data['description'] : null,
                    'password' => '123456',
                    'settings' => [
                        'host_video' => ($data['host_video'] == LiveConsultation::HOST_ENABLE) ? true : false,
                        'participant_video' => ($data['participant_video'] == LiveConsultation::CLIENT_ENABLE) ? true : false,
                        'waiting_room' => true,
                    ],
                ],
            ]);
            $data = json_decode($response->getBody());

            return (array) $data;
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                if (! isset($clientID)) {
                    throw new UnprocessableEntityHttpException(__('messages.live_consultation.credentials_already_use'));
                }

                $userZoomCredential = UserZoomCredential::where('user_id', getLoggedInUserId())->first();

                if (! isset($userZoomCredential)) {
                    throw new UnprocessableEntityHttpException(__('messages.live_consultation.add_credentials_for_zoom_meeting'));
                }
                $refresh_token = $zoomOAuth->refresh_token;
                $client = new Client(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic '.base64_encode($clientID.':'.$secret),
                    ],
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token,
                    ],
                ]);
                $zoomOAuth->update(['refresh_token' => $response->getBody()]);

                $this->createZoomMeeting($data);
            } else {
                if ($e->getCode() == 401) {
                    throw new UnprocessableEntityHttpException(__('messages.live_consultation.invalid_access_token'));
                }
                if ($e->getCode() == 0) {
                    throw new UnprocessableEntityHttpException(__('messages.live_consultation.connect_with_zoom'));
                }
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }
    }

    public function toZoomTimeFormat($dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            \Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function zoomGet($id)
    {
        try {
            $liveCunsultation = LiveConsultation::whereMeetingId($id)->first();
            $liveMeeting = LiveMeeting::whereMeetingId($id)->first();
            $client = new Client(['base_uri' => 'https://api.zoom.us']);

            if (isset($liveCunsultation)) {
                $zoomOAuth = ZoomOAuth::where('user_id', $liveCunsultation->created_by)->first();
            }
            if (isset($liveMeeting)) {
                $zoomOAuth = ZoomOAuth::where('user_id', $liveMeeting->created_by)->first();
            }
            $response = $client->request('GET', '/v2/meetings/'.$id, [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
            ]);

            $data = json_decode($response->getBody());

            return $data;
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.invalid_access_token'));
            }
            if ($e->getCode() == 0) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.connect_with_zoom'));
            }
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function destroyZoomMeeting($meetingId)
    {
        $clientID = config('app.zoom_api_key');
        $secret = config('app.zoom_api_secret');

        $client = new Client(['base_uri' => 'https://api.zoom.us']);

        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            $response = $client->request('DELETE', '/v2/meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
            ]);

            $data = json_decode($response->getBody());

            return $data;
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.invalid_access_token'));
            }
            if ($e->getCode() == 400) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.cannot_delete_meeting_in_progress'));
            }
            if ($e->getCode() == 0) {
                throw new UnprocessableEntityHttpException(__('messages.live_consultation.connect_with_zoom'));
            }
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
