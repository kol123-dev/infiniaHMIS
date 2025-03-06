@extends('layouts.app')
@section('title')
    {{ __('messages.common.appointment_transaction') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <livewire:appointment-transaction-table/>
        </div>
    </div>
@endsection
