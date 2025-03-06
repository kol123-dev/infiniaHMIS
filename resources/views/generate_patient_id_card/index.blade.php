@extends('layouts.app')
@section('title')
    {{ __('messages.patient_id_card.generate_patient_id_card') }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/patient-id-card.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                    <livewire:generate-patient-id-card-table>
                        @include('generate_patient_id_card.create_modal')
                        @include('generate_patient_id_card.show')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
@endsection
