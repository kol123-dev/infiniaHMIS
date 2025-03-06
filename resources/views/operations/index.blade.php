@extends('layouts.app')
@section('title')
    {{ __('messages.operations') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            {{ Form::hidden('operation', route('operations.store'), ['class' => 'addOperationURL']) }}
            {{ Form::hidden('operation-edit', url('operations'), ['class' => 'editOperationURL']) }}
            {{ Form::hidden('operation-text', __('messages.operation.operation'), ['class' => 'operation']) }}
            <livewire:operation-table />
            @include('operations.add_modal')
            @include('operations.edit_modal')
        </div>
    </div>
@endsection
