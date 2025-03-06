@extends('layouts.app')
@section('title')
    {{ __('messages.patient_id_card.new_patient_id_card_template') }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/patient-id-card.css') }}">
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('smart-patient-cards.index') }}"
                class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="background-color: #fff">
                    {{ Form::open(['route' => 'smart-patient-cards.store', 'files' => true]) }}
                    @include('patient_id_card_template.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
