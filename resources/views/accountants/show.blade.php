@extends('layouts.app')
@section('title')
    {{ __('messages.accountant.accountant_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a class="btn btn-primary edit-btn"
                   href="{{ url('accountants/'.$accountant->id.'/edit') }}">{{ __('messages.common.edit') }}</a>
                <a href="{{ route('accountants.index') }}"
                   class="btn btn-outline-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'me-2' : 'ms-2'}}">{{ __('messages.common.back') }}</a>
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
                @include('accountants.show_fields')
        </div>
    </div>
@endsection
@section('page_scripts')
    {{--    js/custom/custom-datatable.js --}}
    {{--    js/accountants/accountants_data_listing.js --}}
@endsection
