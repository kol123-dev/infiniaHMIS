<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePathologyUnitRequest;
use App\Http\Requests\UpdatePathologyUnitRequest;
use App\Models\PathologyParameter;
use Illuminate\Http\Request;
use App\Models\PathologyUnit;
use App\Repositories\PathologyUnitRepository;

class PathologyUnitController extends AppBaseController
{

    /** @var PathologyUnitRepository */
    private $pathologyUnitRepository;

    public function __construct(PathologyUnitRepository $pathologyUnitRepo)
    {
        $this->pathologyUnitRepository = $pathologyUnitRepo;
    }

    public function index(Request $request)
    {
        return view('pathology_units.index');
    }

    public function store(CreatePathologyUnitRequest $request)
    {
        $input = $request->all();
        $this->pathologyUnitRepository->create($input);

        return $this->sendSuccess(__('messages.new_change.pathology_unit').' '.__('messages.common.saved_successfully'));
    }

    public function edit(PathologyUnit $pathologyUnit)
    {
        if (! canAccessRecord(PathologyUnit::class, $pathologyUnit->id)) {
            return $this->sendError(__('messages.flash.not_allow_access_record'));
        }

        return $this->sendResponse($pathologyUnit, __('Pathology Unit retrieved successfully.'));
    }

    public function update(PathologyUnit $pathologyUnit, UpdatePathologyUnitRequest $request)
    {
        $input = $request->all();
        $this->pathologyUnitRepository->update($input, $pathologyUnit->id);

        return $this->sendSuccess(__('messages.new_change.pathology_unit').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(PathologyUnit $pathologyUnit)
    {
        if (! canAccessRecord(PathologyUnit::class, $pathologyUnit->id)) {
            return $this->sendError(__('messages.new_change.pathology_unit_not_found'));
        }

        $pathologyParameterModels = [
            PathologyParameter::class,
        ];
        $result = canDelete($pathologyParameterModels, 'unit_id', $pathologyUnit->id);

        if ($result) {
            return $this->sendError(__('messages.new_change.pathology_unit_cant_deleted'));
        }

        $pathologyUnit->delete();

        return $this->sendSuccess(__('messages.new_change.pathology_unit').' '.__('messages.common.deleted_successfully'));
    }
}
