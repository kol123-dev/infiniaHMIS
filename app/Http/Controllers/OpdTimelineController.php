<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOpdTimelineRequest;
use App\Http\Requests\UpdateOpdTimelineRequest;
use App\Models\OpdTimeline;
use App\Repositories\OpdTimelineRepository;
use Illuminate\Http\Request;

class OpdTimelineController extends AppBaseController
{
    /** @var OpdTimelineRepository */
    private $opdTimelineRepository;

    public function __construct(OpdTimelineRepository $opdTimelineRepo)
    {
        $this->opdTimelineRepository = $opdTimelineRepo;
    }

    public function index(Request $request)
    {
        $opdTimelines = $this->opdTimelineRepository->getTimeLines($request->get('id'));

        return view('opd_timelines.index', compact('opdTimelines'))->render();
    }

    public function store(CreateOpdTimelineRequest $request)
    {
        $input = $request->all();
        $this->opdTimelineRepository->store($input);

        return $this->sendSuccess(__('messages.opd_timeline').' '.__('messages.common.saved_successfully'));
    }

    public function edit(OpdTimeline $opdTimeline)
    {
        return $this->sendResponse($opdTimeline, 'OPD Timeline retrieved successfully.');
    }

    public function update(OpdTimeline $opdTimeline, UpdateOpdTimelineRequest $request)
    {
        $this->opdTimelineRepository->updateOpdTimeline($request->all(), $opdTimeline->id);

        return $this->sendSuccess(__('messages.opd_timeline').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(OpdTimeline $opdTimeline)
    {
        $this->opdTimelineRepository->deleteOpdTimeline($opdTimeline->id);

        return $this->sendSuccess(__('messages.opd_timeline').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(OpdTimeline $opdTimeline)
    {
        $media = $opdTimeline->getMedia(OpdTimeline::OPD_TIMELINE_PATH)->first();

        if ($media) {
            return $media;
        }

        return '';
    }
}
