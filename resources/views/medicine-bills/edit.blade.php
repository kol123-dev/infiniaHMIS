@extends('layouts.app')
@section('title')
    {{ __('messages.medicine_bills.edit_medicine_bill') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('medicine-bills.index') }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
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
                    <div class="alert alert-danger d-none hide" id="validationErrorsBox"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    {{ Form::hidden('uniqueId', count($medicineBill->saleMedicine) + 1, ['id' => 'medicineUniqueId']) }}
                    {{ Form::hidden('associateMedicines', json_encode($medicineList), ['class' => 'associatePurchaseMedicines']) }}
                    {{ Form::hidden('medicineCategories', json_encode($medicineCategoriesList), ['id' => 'showMedicineCategoriesMedicineBill']) }}
                    {{ Form::hidden('medicine_bill_id', $medicineBill->id, ['id' => 'medicineBillId']) }}
                    {{ Form::hidden('payment-status', $medicineBill->payment_status, ['class' => 'payment-status']) }}
                    {{ Form::model($medicineBill, ['route' => ['medicine-bills.update', $medicineBill->id], 'method' => 'patch', 'id' => 'MedicinebillForm']) }}
                    <div class="row">
                        @include('medicine-bills.medicine-table')
                    </div>
                    {{ Form::close() }}
                </div>
                @include('medicine-bills.templates.templates')
            </div>
        </div>
    @endsection
