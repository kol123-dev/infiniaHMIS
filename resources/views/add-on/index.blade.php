@extends('layouts.app')
@section('title')
    {{ __('messages.addon.addon') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="container-fluid">
        <div class="flex text-end">
            {{ Form::hidden('showAddOnCreateUrl', route('addOn.extractZip'), ['id' => 'showAddOnCreateUrl']) }}
            <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadAddOnModal">
                {{ __('messages.addon.upload_addon') }}
            </a>
        </div>
        <livewire:add-on-table />
        @include('add-on.upload_addon_modal')
    </div>
@endsection
