@extends('layouts.app')
@section('title')
    {{ __('messages.appointment.new_appointment') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('appointments.index') }}"
               class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                    <div class="alert alert-danger d-none hide" id="createAppointmentErrorsBox"></div>
                </div>
            </div>
            <div class="card">
                {{ Form::hidden('doctorDepartmentUrl', url('doctors-list'), ['class' => 'doctorDepartmentUrl']) }}
                {{ Form::hidden('doctorChargeUrl', url('doctors-appointment-charge'), ['class' => 'doctorChargeUrl']) }}
                {{ Form::hidden('doctorScheduleList', url('doctor-schedule-list'), ['class' => 'doctorScheduleList']) }}
                {{ Form::hidden('appointmentSaveUrl', route('appointments.store'), ['id' => 'saveAppointmentURLID']) }}
                {{ Form::hidden('appointmentIndexPage', route('appointments.index'), ['class' => 'appointmentIndexPage']) }}
                {{ Form::hidden('isEdit', false, ['class' => 'isEdit']) }}
                {{ Form::hidden('isCreate', true, ['class' => 'isCreate']) }}
                {{ Form::hidden('getBookingSlot', route('get.booking.slot'), ['class' => 'getBookingSlot']) }}
                <div class="card-body p-12">
                    {{ Form::open(['id' => 'creatAppointmentForm']) }}

                    @include('appointments.fields')

                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @include('appointments.templates.appointment_slot')
    </div>
@endsection
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let appointmentOptions = {
            'key': "{{ getPaymentCredentials('razorpay_key') }}",
            'amount': 0,
            'currency': "{{ strtoupper(getCurrentCurrency()) }}",
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': "{{ asset(getLogoUrl()) }}",
            'callback_url': "{{ route('appointment.razorpay.success') }}",
            'prefill': {
                'appointment_id' :'',
                'amount' : '',
                'payment_mode' : '',
            },
            'theme': {
                'color': '#FF8E4B',
            },
            'modal': {
                'ondismiss': function() {
                    // livewire.emit("refresh");
                    $.ajax({
                        type: 'POST',
                        url: route('appointment.razorpay.failed'),
                        data: $("#appointmentForm").serialize(),
                        success: function(result) {
                            if (result.success) {
                                displayErrorMessage(result.message);
                                setTimeout(function () {
                                    window.location.href = $(".appointmentIndexPage").val();
                                }, 2000);
                            }
                        },
                        error: function(result) {
                            displayErrorMessage(result.responseJSON.message)
                        },
                    });
                },
            }
        }
        let stripeKey = '';
        @if (getPaymentCredentials('stripe_key'))
            stripeKey = Stripe('{{ getPaymentCredentials('stripe_key') }}');
        @endif
    </script>
    {{--  backend/js/moment-round/moment-round.js --}}
    {{--  assets/js/appointments/create-edit.js  --}}
@endsection
