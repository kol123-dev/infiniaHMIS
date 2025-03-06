<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\LiveMeetingRequest;
use App\Models\LiveMeeting;
use App\Repositories\LiveMeetingRepository;
use App\Repositories\ZoomRepository;
use Exception;
use Illuminate\Http\Request;

class LiveMeetingController extends AppBaseController
{
    /** @var LiveMeetingRepository */
    private $liveMeetingRepository;

    /** @var ZoomRepository */
    private $zoomRepository;

    public function __construct(LiveMeetingRepository $liveMeetingRepository, ZoomRepository $zoomRepository)
    {
        $this->liveMeetingRepository = $liveMeetingRepository;
        $this->zoomRepository = $zoomRepository;
    }

    public function index()
    {
        $users = $this->liveMeetingRepository->getUsers();
        $status = LiveMeeting::status;

        return view('live_consultations.member_index', compact('users', 'status'));
    }

    public function liveMeetingStore(LiveMeetingRequest $request)
    {
        try {
            $this->liveMeetingRepository->store($request->all());
            $this->liveMeetingRepository->createNotification($request->all());

            return $this->sendSuccess(__('messages.live_meetings').' '.__('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getChangeStatus(Request $request)
    {
        $liveMeeting = LiveMeeting::find($request->get('id'));
        $status = null;

        if ($request->get('statusId') == LiveMeeting::STATUS_AWAITED) {
            $status = LiveMeeting::STATUS_AWAITED;
        } elseif ($request->get('statusId') == LiveMeeting::STATUS_CANCELLED) {
            $status = LiveMeeting::STATUS_CANCELLED;
        } else {
            $status = LiveMeeting::STATUS_FINISHED;
        }

        $liveMeeting->update([
            'status' => $status,
        ]);

        return $this->sendsuccess(__('messages.common.status_updated_successfully'));
    }

    public function getLiveStatus(LiveMeeting $liveMeeting)
    {
        if (getLoggedInUser()->hasRole('Admin') || collect($liveMeeting->members)->contains('id', getLoggedInUser()->id)) {
            $data['liveMeeting'] = LiveMeeting::with('user')->find($liveMeeting->id);

            $zoomRepo = App::make(ZoomRepository::class, ['createdBy' => $liveMeeting->created_by]);
            $data['zoomLiveData'] = $zoomRepo->zoomGet($liveMeeting->meta['id']);

            return $this->sendResponse($data, 'Live Status retrieved successfully.');
        } else {
            return $this->sendError(__('messages.live_meetings').' '.__('messages.common.not_found'));
        }
    }

    public function edit(LiveMeeting $liveMeeting)
    {
        if (getLoggedInUser()->hasRole('Admin') || collect($liveMeeting->members)->contains('id', getLoggedInUser()->id)) {
            $liveMeeting->load('members');
            $meetingUsers = $liveMeeting->members->pluck('id')->toArray();
            $liveMeeting->setAttribute('meetingUsers', $meetingUsers);

            return $this->sendResponse($liveMeeting, 'Live Meeting retrieved successfully.');
        } else {
            return $this->sendError(__('messages.live_meetings').' '.__('messages.common.not_found'));
        }
    }

    public function update(LiveMeetingRequest $request, LiveMeeting $liveMeeting)
    {
        try {
            $this->liveMeetingRepository->edit($request->all(), $liveMeeting);

            return $this->sendSuccess(__('messages.live_meetings').' '.__('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show(LiveMeeting $liveMeeting)
    {
        if (getLoggedInUser()->hasRole('Admin') || collect($liveMeeting->members)->contains('id', getLoggedInUser()->id)) {
            $liveMeeting = LiveMeeting::with(['user'])->find($liveMeeting->id);

            return $this->sendResponse($liveMeeting, 'Live Meeting retrieved successfully.');
        } else {
            return $this->sendError(__('messages.live_meetings').' '.__('messages.common.not_found'));
        }
    }

    public function destroy(LiveMeeting $liveMeeting)
    {
        try {
            if (getLoggedInUser()->hasRole('Admin') || collect($liveMeeting->members)->contains('id', getLoggedInUser()->id)) {
                $this->zoomRepository->destroyZoomMeeting($liveMeeting->meeting_id);
                $liveMeeting->delete();

                return $this->sendSuccess(__('messages.live_meetings').' '.__('messages.common.deleted_successfully'));
            } else {
                return $this->sendError(__('messages.live_meetings').' '.__('messages.common.not_found'));
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
