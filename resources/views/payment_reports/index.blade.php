@extends('layouts.app')
@section('title')
    {{ __('messages.payment.payment_reports') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:payment-report />
        </div>
    </div>
    {{ Form::hidden('paymentReportUrl', url('payment-reports'), ['id' => 'indexPaymentReportUrl', 'class' => 'paymentReportUrl']) }}
@endsection
@section('scripts')
    {{--  assets/js/payment_reports/payments_reports.js --}}
    {{--  assets/js/custom/input_price_format.js --}}
@endsection
