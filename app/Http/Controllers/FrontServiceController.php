<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontServiceRequest;
use App\Http\Requests\UpdateFrontServiceRequest;
use App\Models\FrontService;
use App\Repositories\FrontServiceRepository;

class FrontServiceController extends AppBaseController
{
    /** @var FrontServiceRepository */
    private $frontServiceRepository;

    public function __construct(FrontServiceRepository $frontServiceRepository)
    {
        $this->frontServiceRepository = $frontServiceRepository;
    }

    public function index()
    {
        return view('front_settings.front_services.index');
    }

    public function store(FrontServiceRequest $request)
    {
        try {
            $input = $request->all();
            $this->frontServiceRepository->store($input);

            return $this->sendSuccess(__('messages.front_service').' '.__('messages.common.saved_successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $frontService = FrontService::find($id);

        return $this->sendResponse($frontService, 'FrontService retrieved successfully.');
    }

    public function update($id, UpdateFrontServiceRequest $request)
    {
        try {
            $this->frontServiceRepository->updateFrontService($request->all(), $id);

            return $this->sendSuccess(__('messages.front_service').' '.__('messages.common.updated_successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $frontService = FrontService::find($id);
            $frontService->clearMediaCollection(FrontService::PATH);
            $frontService->delete();

            return $this->sendSuccess(__('messages.front_service').' '.__('messages.common.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
