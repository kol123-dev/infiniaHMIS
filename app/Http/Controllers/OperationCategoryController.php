<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationCategoryRequest;
use App\Http\Requests\UpdateOperationCategoryRequest;
use App\Models\Operation;
use App\Models\OperationCategory;
use App\Repositories\OperationCategoryRepository;
use Illuminate\Http\Request;

class OperationCategoryController extends AppBaseController
{
    private OperationCategoryRepository $categoryRepository;

    public function __construct(OperationCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return view('operation_categories.index');
    }

    public function store(StoreOperationCategoryRequest $request)
    {
        $input = $request->all();
        $this->categoryRepository->create($input);

        return $this->sendSuccess(__('messages.operation_category.operation_category').' '.__('messages.common.saved_successfully'));
    }

    public function edit(OperationCategory $operationCategory)
    {
        return $this->sendResponse($operationCategory, 'Operation Category retrieved successfully.');
    }

    public function update(UpdateOperationCategoryRequest $request, OperationCategory $operationCategory)
    {
        $input = $request->all();
        $this->categoryRepository->update($input, $operationCategory->id);

        return $this->sendSuccess(__('messages.operation_category.operation_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(OperationCategory $operationCategory)
    {
        $operationModels = [
            Operation::class,
        ];

        $result = canDelete($operationModels, 'operation_category_id', $operationCategory->id);

        if ($result) {
            return $this->sendError(__('messages.operation_category.operation_category').' '.__('messages.common.cant_be_deleted'));
        }

        $operationCategory->delete();

        return $this->sendSuccess(__('messages.operation_category.operation_category').' '.__('messages.common.deleted_successfully'));
    }

    public function getOperationName(Request $request)
    {
        return Operation::where('operation_category_id', $request->id)->get()->pluck('id', 'name');
    }
}
