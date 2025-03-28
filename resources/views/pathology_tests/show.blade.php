@extends('layouts.app')
@section('title')
    {{ __('messages.pathology_test.pathology_test_details')}}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            @include('layouts.errors')
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('pathology.test.pdf', $pathologyTest->id) }}"
                    target="_blank"
                    class="btn btn-success {{getCurrentLoginUserLanguageName()=='ar' ? 'ms-2' : 'me-2'}}">{{ __('messages.new_change.print_pathology_test') }}
                 </a>
                <a  class="btn btn-primary"
                    href="{{ route('pathology.test.edit',['pathologyTest' => $pathologyTest->id])}}">{{ __('messages.common.edit') }}</a>
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : 'ms-2'}}">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
                @include('pathology_tests.show_fields')
        </div>
    </div>
@endsection
