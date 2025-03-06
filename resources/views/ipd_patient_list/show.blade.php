@extends('layouts.app')
@section('title')
    {{ __('messages.ipd_patient.ipd_patient_details') }}
@endsection

@section('page_css')
@endsection

@section('css')
{{--    <link href="{{ asset('assets/css/timeline.css') }}" rel="stylesheet" type="text/css"/>--}}
@endsection

@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <div class="text-end mt-4 mt-md-0">
                <a href="{{  route('patient.ipd') }}"
                   class="btn btn-outline-primary ms-2">{{ __('messages.common.back') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                {{Form::hidden('ipdPrescriptionUrl',route('ipd.prescription.index'),['id'=>'showListIpdPrescriptionUrl'])}}
                {{Form::hidden('bootStrapUrl',asset('assets/css/bootstrap.min.css'),['id'=>'showListBootstrapUrl'])}}
                {{Form::hidden('ipdPatientDepartmentId',$ipdPatientDepartment->id,['id'=>'showListIpdPatientDepartmentId'])}}
                {{Form::hidden('ipdTimelinesUrl',route('ipd.timelines.index'),['id'=>'showListIpdTimelinesUrl'])}}
                {{Form::hidden('ipdStripePaymentUrl',url('stripe-charge'),['id'=>'showListIpdStripePaymentUrl'])}}
                {{Form::hidden('ipdPrescriptionUrl',route('ipd.prescription.index'),['id'=>'showIpdPrescriptionUrl'])}}
                {{ Form::hidden('stripeConfigKey', getPaymentCredentials('stripe_key'), ['id' => 'stripeConfigKey']) }}
                {{Form::hidden('ipdPaymentCreateUrl',route('ipd.payments.store'),['id'=>'showIpdPaymentCreateUrl'])}}

                <div class="col-12">
                    @include('flash::message')
                </div>
            </div>
                @include('ipd_patient_list.show_fields')
        </div>
    </div>
    @include('ipd_prescriptions.show_modal')
    @include('ipd_payments.add_modal')
@endsection
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
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
            'callback_url': "{{ route('ipdRazorpay.success') }}",
            'prefill': {
                'ipd_patient_department_id' :'',
                'amount' : '',
                'date' : '',
                'payment_mode' : '',
                'avatar_remove' : '',
                'notes' : '',
                'currency_symbol' : '',
            },
            'theme': {
                'color': '#FF8E4B',
            },
            'modal': {
                'ondismiss': function() {
                    Livewire.dispatch("refresh");
                    displayErrorMessage("{{ __('messages.payment.payment_failed') }}");
                },
            }
        }
        let stripe = '';
        @if (getPaymentCredentials('stripe_key'))
            stripe = Stripe("{{ getPaymentCredentials('stripe_key') }}");
        @endif

        $('#IPDtab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        // store the currently selected tab in the hash value
        $('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
            var id = $(e.target).attr('href').substr(1)
            window.location.hash = id
        })
    </script>
    {{--  assets/js/ipd_patients_list/ipd_diagnosis.js --}}
    {{--  assets/js/ipd_patients_list/ipd_consultant_register.js --}}
    {{--  assets/js/ipd_patients_list/ipd_charges.js --}}
    {{--  assets/js/ipd_patients_list/ipd_prescriptions.js --}}
    {{--  assets/js/ipd_patients_list/ipd_timelines.js --}}
    {{--  ssets/js/custom/input_price_format.js -}}
    {{--  assets/js/ipd_patients_list/ipd_payments.js --}}
    {{--  assets/js/ipd_patients_list/ipd_stripe_payment.js --}}
@endsection
