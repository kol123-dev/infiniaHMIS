<?php

namespace App\Repositories;

use App;
use App\Models\LiveMeeting;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LiveMeetingRepository extends BaseRepository
{
    const MEETING_TYPE_INSTANT = 1;

    const MEETING_TYPE_SCHEDULE = 2;

    const MEETING_TYPE_RECURRING = 3;

    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    protected $fieldSearchable = [
        'consultation_title',
        'consultation_date',
        'consultation_duration_minutes',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return LiveMeeting::class;
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

            $zoomModel = LiveMeeting::create($input);
            $zoomModel->members()->attach($input['staff_list']);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function edit($input, $liveMeeting)
    {
        try {
            $zoomRepo = App::make(ZoomRepository::class, ['createdBy' => $liveMeeting->created_by]);
            $zoomSessionUpdate = $zoomRepo->updateZoomMeeting($input, $liveMeeting);
            $input['created_by'] = $liveMeeting->created_by != getLoggedInUserId() ? $liveMeeting->created_by : getLoggedInUserId();
            $startTime = $input['consultation_date'];
            $input['consultation_date'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');

            $liveMeeting->update($input);
            $liveMeeting->members()->sync($input['staff_list']);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getUsers()
    {
        try {
            $roles = User::orderBy('first_name')->whereHas('roles', function (Builder $query) {
                $query->where('name', '!=', 'Patient');
            })->where('status', '=', 1)->get();

            $result = [];
            foreach ($roles as $role) {
                foreach ($role->roles as $roleName) {
                    $result[$role->id] = $role->full_name.' ('.$roleName->name.')';
                }
            }

            return $result;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createNotification($input = [])
    {
        try {
            $id = $input['staff_list'];
            $users = [];
            foreach ($id as $key => $value) {
                $users[$value] = User::where('id', $value)->pluck('owner_type', 'id')->first();
            }

            foreach ($users as $key => $userId) {
                $userIds[$key] = Notification::NOTIFICATION_FOR[User::getOwnerType($userId)];
            }

            unset($userIds[getLoggedInUserId()]);

            foreach ($userIds as $key => $notification) {
                addNotification([
                    Notification::NOTIFICATION_TYPE['Live Meeting'],
                    $key,
                    $notification,
                    getLoggedInUser()->first_name.' '.getLoggedInUser()->last_name.' has been created a live meeting.',
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
