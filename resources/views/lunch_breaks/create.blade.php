@extends('layouts.app')
@section('title')
    {{ __('messages.lunch_break.add_break') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h1>@yield('title')</h1>
            <a href="{{ route('breaks.index') }}"
                class="btn btn-outline-primary float-end">{{ __('messages.common.back') }}</a>
        </div>
        <div class="col-12">
            @include('layouts.errors')
            @include('flash::message')
        </div>
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'breaks.store', 'id' => 'doctorBreakForm', 'class' => 'doctorBreakForm']) }}
                <div class="card-body p-0">
                    @include('lunch_breaks.fields')
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
