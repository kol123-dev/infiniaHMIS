@extends('layouts.app')
@section('title')
    {{ __('messages.bill.manual_bill') }}
@endsection
@section('page_css')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('flash::message')
                    @include('layouts.errors')
                    <livewire:manual-billing-payment-table>
                </div>
            </div>
        </div>
    </div>
@endsection
