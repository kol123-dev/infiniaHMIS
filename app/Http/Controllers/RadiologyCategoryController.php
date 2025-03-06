<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRadiologyCategoryRequest;
use App\Http\Requests\UpdateRadiologyCategoryRequest;
use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;
use App\Repositories\RadiologyCategoryRepository;

class RadiologyCategoryController extends AppBaseController
{
    /** @var RadiologyCategoryRepository */
    private $radiologyCategoryRepository;

    public function __construct(RadiologyCategoryRepository $radiologyCategoryRepo)
    {
        $this->radiologyCategoryRepository = $radiologyCategoryRepo;
    }

    public function index()
    {
        return view('radiology_categories.index');
    }

    public function store(CreateRadiologyCategoryRequest $request)
    {
        $input = $request->all();
        $this->radiologyCategoryRepository->create($input);

        return $this->sendSuccess(__('messages.radiology_category.radiology_categories').' '.__('messages.common.saved_successfully'));
    }

    public function edit(RadiologyCategory $radiologyCategory)
    {
        return $this->sendResponse($radiologyCategory, 'Radiology Category retrieved successfully.');
    }

    public function update(RadiologyCategory $radiologyCategory, UpdateRadiologyCategoryRequest $request)
    {
        $input = $request->all();
        $this->radiologyCategoryRepository->update($input, $radiologyCategory->id);

        return $this->sendSuccess(__('messages.radiology_category.radiology_categories').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(RadiologyCategory $radiologyCategory)
    {
        $radiologyCategoryModels = [
            RadiologyTest::class,
        ];
        $result = canDelete($radiologyCategoryModels, 'category_id', $radiologyCategory->id);

        if ($result) {
            return $this->sendError(__('messages.radiology_category.radiology_categories').' '.__('messages.common.cant_be_deleted'));
        }

        $radiologyCategory->delete();

        return $this->sendSuccess(__('messages.radiology_category.radiology_categories').' '.__('messages.common.deleted_successfully'));
    }
}
