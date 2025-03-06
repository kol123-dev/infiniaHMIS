<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAddCustomFieldRequest;
use App\Models\AddCustomFields;
use Illuminate\Http\Request;

class AddCustomFieldsController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('add_custom_fields.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAddCustomFieldRequest $request)
    {
        $input = $request->all();
        $input['is_required'] = isset($input['is_required']) == 0 ? 0 : 1;
        AddCustomFields::create($input);

        return $this->sendSuccess(__('messages.custom_field.custom_field').' '.__('messages.common.saved_successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customField = AddCustomFields::find($id);

        return $this->sendResponse($customField, 'data retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAddCustomFieldRequest $request, $id)
    {
        $customField = AddCustomFields::find($id);
        $input = $request->all();
        $input['is_required'] = isset($input['is_required']) == 0 ? 0 : 1;

        $customField->update($input);

        return $this->sendSuccess(__('messages.custom_field.custom_field').' '.__('messages.common.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AddCustomFields::where('id', $id)->delete();

        return $this->sendSuccess(__('messages.custom_field.custom_field').' '.__('messages.common.deleted_successfully'));
    }
}
