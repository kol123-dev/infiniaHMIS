@extends('layouts.app')
@section('title')
    {{ __('messages.invoice.invoice_details')}}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a class="btn btn-primary edit-btn"
                   href="{{ route('invoices.edit', ['invoice' => $invoice->id]) }}">{{ __('messages.common.edit') }}</a>
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : 'ms-2'}}">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="card">
                <div class="card-body">
                        @include('invoices.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection
