@extends('layouts.app')
@section('title')
    {{ __('messages.new_change.pathology_units') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            {{ Form::hidden('pathologyCategoryCreateUrl', route('pathology.unit.store'), ['id' => 'createPathologyUnitURL']) }}
            {{ Form::hidden('pathologyUnitUrl', url('pathology-units'), ['id' => 'pathologyUnitURL']) }}
            {{ Form::hidden('pathologyUnitLang',__('messages.new_change.pathology_unit'), ['id' => 'pathologyUnitLang']) }}
            <livewire:pathology-unit-table/>
            @include('pathology_units.add_modal')
            @include('pathology_units.edit_modal')
        </div>
    </div>
@endsection
