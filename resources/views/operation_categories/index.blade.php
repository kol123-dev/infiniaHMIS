@extends('layouts.app')
@section('title')
    {{ __('messages.operation_category.operation_categories') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{ Form::hidden('operationCategoryCreateUrl', route('operation.category.store'), ['id' => 'operationCategoryCreateUrl']) }}
            {{ Form::hidden('operationCategoryUrl', url('operation-categories'), ['id' => 'operationCategoryUrl']) }}
            {{ Form::hidden('operationCategory', __('messages.operation_category.operation_category'), ['id' => 'operationCategory']) }}
            <livewire:operation-category-table/>
            @include('operation_categories.modal')
            @include('operation_categories.edit_modal')
        </div>
    </div>
@endsection

