@extends('layouts.app')
@section('title')
    {{ __('messages.new_change.pathology_parameters') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            {{ Form::hidden('pathologyParameterCreateUrl', route('pathology.parameter.store'), ['id' => 'createPathologyParameterURL']) }}
            {{ Form::hidden('pathologyParameterUrl', url('pathology-parameters'), ['id' => 'pathologyParameterURL']) }}
            {{ Form::hidden('pathologyParameterLang',__('messages.new_change.pathology_parameter'), ['id' => 'pathologyParameterLang']) }}
            <livewire:pathology-parameter-table/>
            @include('pathology_parameter.modal')
            @include('pathology_parameter.edit_modal')
        </div>
    </div>
@endsection
