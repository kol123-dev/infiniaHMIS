<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePathologyCategoryRequest;
use App\Http\Requests\UpdatePathologyCategoryRequest;
use App\Models\PathologyCategory;
use App\Models\PathologyTest;
use App\Repositories\PathologyCategoryRepository;

class PathologyCategoryController extends AppBaseController
{
    /** @var PathologyCategoryRepository */
    private $pathologyCategoryRepository;

    public function __construct(PathologyCategoryRepository $pathologyCategoryRepo)
    {
        $this->pathologyCategoryRepository = $pathologyCategoryRepo;
    }

    public function index()
    {
        return view('pathology_categories.index');
    }

    public function store(CreatePathologyCategoryRequest $request)
    {
        $input = $request->all();
        $this->pathologyCategoryRepository->create($input);

        return $this->sendSuccess(__('messages.pathology_categories').' '.__('messages.common.saved_successfully'));
    }

    public function edit(PathologyCategory $pathologyCategory)
    {
        return $this->sendResponse($pathologyCategory, 'Pathology Category retrieved successfully.');
    }

    public function update(PathologyCategory $pathologyCategory, UpdatePathologyCategoryRequest $request)
    {
        $input = $request->all();
        $this->pathologyCategoryRepository->update($input, $pathologyCategory->id);

        return $this->sendSuccess(__('messages.pathology_categories').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(PathologyCategory $pathologyCategory)
    {
        $pathologyCategoryModels = [
            PathologyTest::class,
        ];
        $result = canDelete($pathologyCategoryModels, 'category_id', $pathologyCategory->id);

        if ($result) {
            return $this->sendError(__('messages.pathology_categories').' '.__('messages.common.cant_be_deleted'));
        }

        $pathologyCategory->delete();

        return $this->sendSuccess(__('messages.pathology_categories').' '.__('messages.common.deleted_successfully'));
    }
}
