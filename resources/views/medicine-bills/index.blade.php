@extends('layouts.app')
@section('title')
    {{ __('messages.medicine_bills.medicine_bill') }}
@endsection

@section('content')
    <div class="container-fluid">
        @include('flash::message')
        {{Form::hidden('billUrl',route('bills.index'),['id'=>'indexBillUrl','class'=>'billUrl'])}}
        {{Form::hidden('patientUrl',url('patients'),['id'=>'indexPatientUrl','class'=>'patientUrl'])}}
        {{ Form::hidden('billLang', __('messages.delete.bill'), ['id' => 'billLang']) }}
        <div class="d-flex flex-column">
            <livewire:medicine-bill-table/>
        </div>
    </div>
@endsection
