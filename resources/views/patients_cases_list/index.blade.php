@extends('layouts.app')
@section('title')
    {{ __('messages.patients_cases') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:case-table />
        </div>
    </div>
@endsection
{{-- JS File :- assets/js/patient_cases_list/patient_cases_list.js --}}
