{{-- <div class="overflow-hidden">
    <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap" id="myTab" role="tablist">
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <a class="nav-link text-active-primary me-6 active" data-bs-toggle="tab" href="#stripeForm">
                {{__('messages.bill.stripe')}}
            </a>
        </li>
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <a class="nav-link text-active-primary me-6" data-bs-toggle="tab" href="#PayPalForm">{{__('messages.paypal')}}</a>
        </li>
        <li class="nav-item position-relative me-7 mb-3" role="presentation">
            <a class="nav-link text-active-primary me-6" data-bs-toggle="tab" href="#RazorPayForm">{{__('messages.razorpay')}}</a>
        </li>
    </ul>
</div>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="stripeForm" role="tabpanel">
        @include('payment_gateway.stripe')
    </div>
    <div class="tab-pane fade" id="PayPalForm" role="tabpanel">
        @include('payment_gateway.paypal')
    </div>
    <div class="tab-pane fade" id="RazorPayForm" role="tabpanel">
        @include('payment_gateway.razorpay')
    </div>
</div> --}}

<div class="card">
    <div class="card-body">
        {{ Form::open(['route' => 'payment-gateways.store', 'id' => 'UserCredentialsSettings', 'class' => 'form']) }}
        <div class="row">
            {{-- STRIPE --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.bill.stripe') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="stripe_enable" class="form-check-input stripe-enable" value="1"
                        {{ !empty($credentials['stripe_enable']) == '1' ? 'checked' : '' }} id="stripeEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="stripe-div d-none col-12">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('stripe_key', __('messages.stripe_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('stripe_key', $credentials['stripe_key'] ?? null, ['class' => 'form-control', 'id' => 'stripeKey', 'placeholder' => __('messages.stripe_key')]) }}

                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('stripe_secret', __('messages.stripe_secret') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('stripe_secret', $credentials['stripe_secret'] ?? null, ['class' => 'form-control', 'id' => 'stripeSecret', 'placeholder' => __('messages.stripe_secret')]) }}
                    </div>
                </div>
            </div>

            {{-- PAYPAL --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.paypal') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="paypal_enable" class="form-check-input paypal-enable" value="1"
                        {{ !empty($credentials['paypal_enable']) == '1' ? 'checked' : '' }} id="paypalEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="paypal-div d-none  col-12">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('paypal_client_id', __('messages.paypal_client_id') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('paypal_client_id', !empty($credentials['paypal_client_id']) ? $credentials['paypal_client_id'] : null, ['class' => 'form-control', 'id' => 'paypalKey', 'placeholder' => __('messages.paypal_client_id')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('paypal_secret', __('messages.paypal_secret') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('paypal_secret', !empty($credentials['paypal_secret']) ? $credentials['paypal_secret'] : null, ['class' => 'form-control', 'id' => 'paypalSecret', 'placeholder' => __('messages.paypal_secret')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('paypal_mode', __('messages.paypal_mode') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('paypal_mode', !empty($credentials['paypal_mode']) ? $credentials['paypal_mode'] : null, ['class' => 'form-control', 'id' => 'paypalMode', 'placeholder' => __('messages.paypal_mode')]) }}
                    </div>
                </div>
            </div>

            {{-- Razorpay --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.razorpay') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="razorpay_enable" class="form-check-input razorpay_enable"
                        value="1" {{ !empty($credentials['razorpay_enable']) == '1' ? 'checked' : '' }}
                        id="razorpayEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="razorpay-div d-none col-12">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('razorpay_key', __('messages.razorpay_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('razorpay_key', $credentials['razorpay_key'] ?? null, ['class' => 'form-control required', 'id' => 'razorpayKey', 'placeholder' => __('messages.razorpay_key')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('razorpay_secret', __('messages.razorpay_secret') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('razorpay_secret', $credentials['razorpay_secret'] ?? null, ['class' => 'form-control', 'id' => 'razorpaySecret', 'placeholder' => __('messages.razorpay_secret')]) }}
                    </div>
                </div>
            </div>

            {{-- FlutterWave --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.flutterwave') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="flutterwave_enable" class="form-check-input flutterwave_enable"
                        value="1" {{ !empty($credentials['flutterwave_enable']) == '1' ? 'checked' : '' }}
                        id="flutterWaveEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="flutterWave-div d-none col-12">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('flutterwave_public_key', __('messages.flutterwave_public_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('flutterwave_public_key', !empty($credentials['flutterwave_public_key']) ? $credentials['flutterwave_public_key'] : null, ['class' => 'form-control', 'id' => 'flutterwavePublicKey', 'placeholder' => __('messages.flutterwave_public_key')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('flutterwave_secret_key', __('messages.flutterwave_secret_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('flutterwave_secret_key', !empty($credentials['flutterwave_secret_key']) ? $credentials['flutterwave_secret_key'] : null, ['class' => 'form-control', 'id' => 'flutterwaveSecretKey', 'placeholder' => __('messages.flutterwave_secret_key')]) }}
                    </div>
                </div>
            </div>
            {{-- End FlutterWave --}}
            {{-- Phonepe --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.phonepe') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="phone_pe_enable" class="form-check-input phone_pe_enable"
                        value="1" {{ !empty($credentials['phone_pe_enable']) == '1' ? 'checked' : '' }}
                        id="phonePeEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="d-none col-12 phonepe-div">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_merchant_id', __('messages.phonepe_merchant_id') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_merchant_id', $credentials['phonepe_merchant_id'] ?? null, ['class' => 'form-control  phonepe_merchant_id ', 'placeholder' => __('messages.phonepe_merchant_id')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_merchant_user_id', __('messages.phonepe_merchant_user_id') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_merchant_user_id', !empty($credentials['phonepe_merchant_user_id']) ? $credentials['phonepe_merchant_user_id'] : null, ['class' => 'form-control phonepe_merchant_user_id ', 'placeholder' => __('messages.phonepe_merchant_user_id')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_env', __('messages.phonepe_env') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_env', $credentials['phonepe_env'] ?? null, ['class' => 'form-control  phonepe_env ', 'placeholder' => __('messages.phonepe_env')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_salt_key', __('messages.phonepe_salt_key') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_salt_key', $credentials['phonepe_salt_key'] ?? null, ['class' => 'form-control phonepe_salt_key ', 'placeholder' => __('messages.phonepe_salt_key')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_salt_index', __('messages.phonepe_salt_index') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_salt_index', $credentials['phonepe_salt_index'] ?? null, ['class' => 'form-control  phonepe_salt_index ', 'placeholder' => __('messages.phonepe_salt_index')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phonepe_merchant_transaction_id', __('messages.phonepe_merchant_transaction_id') . ':', ['class' => 'form-label mb-3 required']) }}
                        {{ Form::text('phonepe_merchant_transaction_id', $credentials['phonepe_merchant_transaction_id'] ?? null, ['class' => 'form-control phonepe_merchant_transaction_id ', 'placeholder' => __('messages.phonepe_merchant_transaction_id')]) }}
                    </div>
                </div>
            </div>
            {{-- Paystack --}}
            <div class="col-12 d-flex align-items-center">
                <span class="form-label my-3">{{ __('messages.paystack') . ' :' }}</span>
                <label class="form-check form-switch form-switch-sm ms-3">
                    <input type="checkbox" name="paystack_enable" class="form-check-input paystack_enable"
                        value="1" {{ !empty($credentials['paystack_enable']) == '1' ? 'checked' : '' }}
                        id="paystackEnable">
                    <span class="custom-switch-indicator"></span>
                </label>
            </div>
            <div class="paystack-div d-none col-12">
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">

                        {{ Form::label('paystack_public_key', __('messages.paystack_public_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('paystack_public_key', !empty($credentials['paystack_public_key']) ? $credentials['paystack_public_key'] : null, ['class' => 'form-control', 'id' => 'paystackPublicKey', 'placeholder' => __('messages.paystack_public_key')]) }}

                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('paystack_secret_key', __('messages.paystack_secret_key') . ':', ['class' => 'form-label required']) }}
                        {{ Form::text('paystack_secret_key', !empty($credentials['paystack_secret_key']) ? $credentials['paystack_secret_key'] : null, ['class' => 'form-control', 'id' => 'paystackSecretKey', 'placeholder' => __('messages.paystack_secret_key')]) }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"
                    id="userCredentialSettingBtn">{{ __('messages.common.save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
