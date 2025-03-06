<?php

namespace App\Repositories;

use App;
use App\Events\CreateGoogleEvent;
use App\Models\Doctor;
use App\Models\IpdPatientDepartment;
use App\Models\LiveConsultation;
use App\Models\Notification;
use App\Models\OpdPatientDepartment;
use App\Models\Patient;
use App\Models\User;
use App\Models\UserGoogleEventSchedule;
use App\Models\UserZoomCredential;
use Carbon\Carbon;
use DB;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LiveConsultationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'doctor_id',
        'patient_id',
        'consultation_title',
        'consultation_date',
        'consultation_duration_minutes',
        'type',
        'type_number',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return LiveConsultation::class;
    }

    public function getTypeNumber($input)
    {
        if ($input['consultation_type'] == LiveConsultation::OPD) {
            return OpdPatientDepartment::where('patient_id', $input['patient_id'])->pluck('opd_number', 'id');
        } else {
            return IpdPatientDepartment::where('patient_id', $input['patient_id'])->pluck('ipd_number', 'id');
        }
    }

    public function store($input)
    {
        $zoomRepo = App::makeWith(ZoomRepository::class, ['createdBy' => getLoggedInUserId()]);

        try {
            $input['created_by'] = getLoggedInUserId();
            $startTime = $input['consultation_date'];
            $input['consultation_date'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');
            $zoom = $zoomRepo->createZoomMeeting($input);
            $input['password'] = $zoom['password'];
            $input['meeting_id'] = $zoom['id'];
            $input['meta'] = $zoom;

            $zoomModel = LiveConsultation::create($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function edit($input, $liveConsultation)
    {
        try {

            DB::beginTransaction();

            if($input['platform_type'] == LiveConsultation::GOOGLE_MEET){
                $newConsultationDateTime = \Carbon\Carbon::parse($input['consultation_date']);
                $oldConsultationDateTime = \Carbon\Carbon::parse($liveConsultation->consultation_date);

                if ($newConsultationDateTime->ne($oldConsultationDateTime) || $input['consultation_duration_minutes'] != $liveConsultation->consultation_duration_minutes) {
                    $oldGoogleMeetingData = UserGoogleEventSchedule::where(['user_id' => getLoggedInUserId(), 'google_live_consultation_id' => $liveConsultation->id])->first();

                    $oldGoogleMeetingData->delete();
                    $liveConsultation->delete();

                    $this->googleMeetStore($input);
                }else{
                    $input['created_by'] = getLoggedInUserId();
                    $input['created_by'] = $liveConsultation->created_by != getLoggedInUserId() ? $liveConsultation->created_by : getLoggedInUserId();
                    $startTime = $input['consultation_date'];
                    $input['consultation_date'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');
                    $liveConsultation->update($input);
                }
            }else{
                $zoomRepo = App::make(ZoomRepository::class, ['createdBy' => $liveConsultation->created_by]);
                $zoomSessionUpdate = $zoomRepo->updateZoomMeeting($input, $liveConsultation);
                $input['created_by'] = getLoggedInUserId();
                $input['created_by'] = $liveConsultation->created_by != getLoggedInUserId() ? $liveConsultation->created_by : getLoggedInUserId();
                $startTime = $input['consultation_date'];
                $input['consultation_date'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');

                $zoomModel = $liveConsultation->update($input);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function createUserZoom($input)
    {
        try {
            UserZoomCredential::updateOrCreate([
                'user_id' => getLoggedInUserId(),
            ], [
                'user_id' => getLoggedInUserId(),
                'zoom_api_key' => $input['zoom_api_key'],
                'zoom_api_secret' => $input['zoom_api_secret'],
            ]);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createNotification($input = [])
    {
        try {
            $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();
            $doctor = Doctor::with('doctorUser')->where('id', $input['doctor_id'])->first();
            $userIds = [
                $doctor->user_id => Notification::NOTIFICATION_FOR[Notification::DOCTOR],
                $patient->user_id => Notification::NOTIFICATION_FOR[Notification::PATIENT],
            ];

            $adminUser = User::role('Admin')->first();
            $allUsers = $userIds + [$adminUser->id => Notification::NOTIFICATION_FOR[Notification::ADMIN]];
            $users = getAllNotificationUser($allUsers);

            foreach ($users as $key => $notification) {
                if ($notification == Notification::NOTIFICATION_FOR[Notification::PATIENT]) {
                    $title = $patient->patientUser->full_name.' your live consultation has been created by '.$doctor->doctorUser->full_name.'.';
                } else {
                    $title = $patient->patientUser->full_name.' live consultation has been booked.';
                }
                addNotification([
                    Notification::NOTIFICATION_TYPE['Live Consultation'],
                    $key,
                    $notification,
                    $title,
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function filter($status)
    {
        if ($status == 'all') {
            return LiveConsultation::where('patient_id',
                getLoggedInUser()->owner_id)->with('patient', 'doctor', 'user')->orderBy('id', 'desc')->get();
        } elseif ($status == 'awaited') {
            return LiveConsultation::where('status',
                LiveConsultation::STATUS_AWAITED)
                ->where('patient_id', getLoggedInUser()->owner_id)
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($status == 'cancelled') {
            return LiveConsultation::with('patient', 'doctor', 'user')->where('status',
                LiveConsultation::STATUS_CANCELLED)->where('patient_id', getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();
        } elseif ($status == 'finished') {
            return LiveConsultation::with('patient', 'doctor', 'user')->where('status',
                LiveConsultation::STATUS_FINISHED)->where('patient_id', getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();
        } else {
            return false;
        }
    }

    public function googleMeetStore($input)
    {
        try {
            $input['created_by'] = getLoggedInUserId();
            $startTime = $input['consultation_date'];
            $input['consultation_date'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');
            $input['password'] = "123456";

            $liveConsultation = LiveConsultation::create($input);
            CreateGoogleEvent::dispatch($liveConsultation->id);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
