@extends('layouts.app')
@section('title')
    {{ __('messages.custom_field.add_custom_field') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')

        {{ Form::hidden('add-custom-fields', route('add-custom-fields.store'), ['class' => 'addCustomFieldURL']) }}
        {{Form::hidden('addCustomFieldUrl',url('add-custom-fields'),['id'=>'indexAddCustomFieldURL'])}}
        {{ Form::hidden('AddCustomFields', __('messages.custom_field.custom_field'), ['id' => 'customField']) }}

        <div class="d-flex flex-column">
            <livewire:add-custom-field-table />
        </div>
        @include('add_custom_fields.add-custom-field-modal')
        @include('add_custom_fields.edit_custom_field_modal')
    </div>
@endsection
