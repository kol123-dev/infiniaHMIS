@extends('layouts.app')
@section('title')
    {{ __('messages.pathology_test.new_pathology_test') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('pathology.test.index') }}"
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
                    @include('flash::message')
                </div>
            </div>
            <div class="card">
                {{ Form::hidden('pathologyTestUrl', url('pathology-tests'), ['class' => 'pathologyTestActionURL']) }}
                {{ Form::hidden('uniqueId',2,['id'=>'parameterUniqueId'])}}
                {{ Form::hidden('associateParameters',json_encode($parameterList),['class'=>'associateParameters'])}}
                <div class="card-body p-12">
                    {{ Form::open(['route' => 'pathology.test.store', 'id' => 'createPathologyTest']) }}

                    @include('pathology_tests.fields')

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @include('pathology_tests.templates.templates')
@endsection
{{-- JS File :- assets/js/pathology_tests/create-edit.js
                assets/js/custom/input_price_format.js
--}}
