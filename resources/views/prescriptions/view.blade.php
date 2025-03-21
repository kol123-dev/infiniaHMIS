@extends('layouts.app')
@section('title')
    {{ __('messages.prescription.prescription_details') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0"> @yield('title') </h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{ route('prescriptions.pdf',$prescription['prescription']) }}"
                   target="_blank"
                   class="btn btn-success {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-2' : 'me-2'}} edit-btn">{{ __('messages.ipd_patient_prescription.print_prescription') }}
                </a>
                @php
                    $medicineBill = App\Models\MedicineBill::whereModelType('App\Models\Prescription')->whereModelId( $prescription['prescription']->id)->first();
                @endphp
                @role('Admin')
                @if(isset($medicineBill->payment_status) && $medicineBill->payment_status == false)
                    <a class="btn btn-primary edit-btn "
                       href="{{route('prescriptions.edit',['prescription' => $prescription['prescription']->id])}}">
                        {{ __('messages.common.edit') }}
                    </a>
                @endif
                @endrole
                <a href="{{ url()->previous()}}"
                   class="btn btn-outline-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'me-2' : 'ms-2'}}">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <div class="card">
                <div class="card-body">
                    @include('prescriptions.view_fields')
                </div>
            </div>
        </div>
    </div>
@endsection
