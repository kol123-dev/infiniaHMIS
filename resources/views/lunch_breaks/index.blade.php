@extends('layouts.app')
@section('title')
    {{__('messages.lunch_break.lunch_breaks')}}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <livewire:lunch-break-table/>
        </div>
    </div>
@endsection
