@extends('layouts.app')
@section('title')
    {{ __('messages.payment_gateways') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <h3>{{__('messages.payment_gateways')}}</h3>
                @include('payment_gateway.create')
            </div>
        </div>
    </div>
@endsection
