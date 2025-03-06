@extends('layouts.app')
@section('title')
    {{ __('messages.purchase_medicine.purchase_medicine') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="{{ route('medicine-purchase.index') }}"
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
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    {{ Form::hidden('uniqueId', 2, ['id' => 'purchaseUniqueId']) }}
                    {{ Form::hidden('associateMedicines', json_encode($medicineList), ['class' => 'associatePurchaseMedicines']) }}
                    {{ Form::open(['id' => 'purchaseMedicineFormId']) }}
                    <div class="row">
                        @include('purchase-medicines.fields')
                    </div>
                    {{ Form::close() }}
                </div>
                @include('purchase-medicines.templates.templates')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let options = {
            'key': "{{ getPaymentCredentials('razorpay_key') }}",
            'amount': 0,
            'currency': "{{ strtoupper(getCurrentCurrency()) }}",
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': "{{ asset(getLogoUrl()) }}",
            'callback_url': "{{ route('purchase.medicine.razorpay.success') }}",
            'prefill': {
                'amount': '',
                'currency_symbol': '',
            },
            'theme': {
                'color': '#FF8E4B',
            },
            'modal': {
                'ondismiss': function() {
                    $.ajax({
                        type: 'POST',
                        url: route('purchase.medicine.razorpay.fail'),
                        data: $("#purchaseMedicineFormId").serialize(),
                        success: function(result) {
                            if (result.success) {
                                displayErrorMessage(result.message);
                                setTimeout(function() {
                                    window.location.href = route('medicine-purchase.index');
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
            stripe = Stripe("{{ getPaymentCredentials('stripe_key') }}");
        @endif
    </script>
@endsection
