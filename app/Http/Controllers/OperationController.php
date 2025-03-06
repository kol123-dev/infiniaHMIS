<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;
use App\Models\OperationCategory;

class OperationController extends AppBaseController
{
    public function index()
    {
        $operation_categories = OperationCategory::get()->pluck('name', 'id')->toArray();

        return view('operations.index', compact('operation_categories'));
    }

    public function store(CreateOperationRequest $request)
    {
        $input = $request->all();

        Operation::create($input);

        return $this->sendSuccess(__('messages.operation.operation').' '.__('messages.common.saved_successfully'));
    }

    public function edit($id)
    {
        $operation = Operation::where('id', $id)->first();

        return $this->sendResponse($operation, 'data retrieved successfully.');
    }

    public function update(UpdateOperationRequest $request, $id)
    {
        $operation = Operation::where('id', $id)->first();

        $operation->update($request->all());

        return $this->sendSuccess(__('messages.operation.operation').' '.__('messages.common.updated_successfully'));
    }

    public function delete($id)
    {
        Operation::where('id', $id)->delete();

        return $this->sendSuccess(__('messages.operation.operation').' '.__('messages.common.deleted_successfully'));
    }
}
