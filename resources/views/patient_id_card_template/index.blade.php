@extends('layouts.app')
@section('title')
    {{ __('messages.patient_id_card.patient_id_card') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <livewire:patient-id-card-template-table>
    </div>
@endsection
@section('scripts')
@endsection
