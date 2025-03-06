@extends('layouts.app')
@section('title')
    {{ __('messages.bill.bills') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column livewire-table">
            @include('flash::message')
            <livewire:bill-table/>
        </div>
    </div>
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
            'callback_url': "{{ route('razorpay.success') }}",
            'prefill': {
                'billID': '',
            },
            'theme': {
                'color': '#FF8E4B',
            },
            'modal': {
                'ondismiss': function() {
                    Livewire.dispatch("refresh");
                    displayErrorMessage('{{ __('messages.payment.payment_failed') }}');
                },
            }
        }

        let stripe = '';
        @if (getPaymentCredentials('stripe_key'))
            stripe = Stripe('{{ getPaymentCredentials('stripe_key') }}');
        @endif
    </script>
@endsection
{{-- JS File :- assets/js/employee/bill.js --}}
