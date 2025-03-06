<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePathologyParameterRequest;
use App\Http\Requests\UpdatePathologyParameterRequest;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterItem;
use App\Repositories\PathologyParameterRepository;
use Illuminate\Http\Request;

class PathologyParameterController extends AppBaseController
{
    /** @var PathologyParameterRepository */
    private $pathologyParameterRepository;

    public function __construct(PathologyParameterRepository $pathologyParameterRepo)
    {
        $this->pathologyParameterRepository = $pathologyParameterRepo;
    }

    public function index(Request $request)
    {
        $unit = $this->pathologyParameterRepository->getPathologyUnitData();

        return view('pathology_parameter.index', compact('unit'));
    }

    public function store(CreatePathologyParameterRequest $request)
    {
        $input = $request->all();
        $this->pathologyParameterRepository->create($input);

        return $this->sendSuccess(__('messages.new_change.pathology_parameter').' '.__('messages.common.saved_successfully'));
    }

    public function edit(PathologyParameter $pathologyParameter)
    {
        if (! canAccessRecord(PathologyParameter::class, $pathologyParameter->id)) {
            return $this->sendError(__('messages.flash.not_allow_access_record'));
        }

        return $this->sendResponse($pathologyParameter, __('messages.flash.pathology_category_retrieved'));
    }

    public function update(PathologyParameter $pathologyParameter, UpdatePathologyParameterRequest $request)
    {
        $input = $request->all();
        $this->pathologyParameterRepository->update($input, $pathologyParameter->id);

        return $this->sendSuccess(__('messages.new_change.pathology_parameter').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(PathologyParameter $pathologyParameter)
    {
        if (! canAccessRecord(PathologyParameter::class, $pathologyParameter->id)) {
            return $this->sendError(__('messages.new_change.pathology_parameter_not_found'));
        }

        $pathologyParameterModels = [
            PathologyParameterItem::class,
        ];
        $result = canDelete($pathologyParameterModels, 'parameter_id', $pathologyParameter->id);

        if ($result) {
                return $this->sendError(__('messages.new_change.pathology_parameter_cant_deleted'));
        }

        $pathologyParameter->delete();

        return $this->sendSuccess(__('messages.new_change.pathology_parameter').' '.__('messages.common.deleted_successfully'));
    }
}
