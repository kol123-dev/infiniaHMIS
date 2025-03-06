@extends('layouts.app')
@section('title')
    {{ __('messages.medicine_bills.add_medicine_bill') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div>
                <a href="javascript:void(0)" class="btn btn-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'ms-3' : 'me-3'}} add-patient-modal">{{ __('messages.patient.new_patient') }}</a>
                <a href="{{ route('medicine-bills.index') }}" class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
            </div>
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
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    {{Form::hidden('uniqueId',2,['id'=>'medicineUniqueId'])}}
                    {{ Form::hidden('associateMedicines', json_encode($medicineList), ['class' => 'associatePurchaseMedicines']) }}
                    {{ Form::hidden('medicineCategories', json_encode($medicineCategoriesList), ['id' => 'showMedicineCategoriesMedicineBill']) }}

                    {{ Form::open(['id' => 'createMedicinebillFormId']) }}
                        @include('medicine-bills.medicine-table')
                    {{ Form::close() }}
                </div>
                @include('medicine-bills.templates.templates')
            </div>
        </div>
    </div>
    @include('medicine-bills.add_patient_modal')
@endsection
@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let options = {
            'key': "{{ getPaymentCredentials('razorpay_key') }}",
            'amount': 0,
            'currency': "{{ strtoupper(getCurrentCurrency()) }}",
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': "{{ asset(getLogoUrl()) }}",
            'callback_url': "{{ route('medicine.bill.razorpay.success') }}",
            'prefill': {
                'billID': '',
            },
            'theme': {
                'color': '#FF8E4B',
            },
            'modal': {
                'ondismiss': function() {
                    $.ajax({
                        type: 'POST',
                        url: route('medicine.bill.razorpay.failed'),
                        data: $("#createMedicinebillFormId").serialize(),
                        success: function(result) {
                            if (result.success) {
                                displayErrorMessage(result.message);
                                setTimeout(function() {
                                    window.location.href = route('medicine-bills.index');
                                }, 1500)
                            }
                        },
                        error: function(result) {
                            displayErrorMessage(result.responseJSON.message)
                        },
                    });
                },
            }
        }

        let stripe = '';
        @if (getPaymentCredentials('stripe_key'))
            stripe = Stripe('{{ getPaymentCredentials('stripe_key') }}');
        @endif
    </script>
@endsection
