{{ Form::open(['route' => 'payment-gateways.store', 'class' => '']) }}
<div class="row mb-5">
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('PayPal Client ID', __('messages.paypal_client_id') . ':', ['class' => 'form-label']) }}
            {{ Form::text('paypal_client_id', $credentials['paypal_client_id'] ?? null, ['placeholder' => __('messages.paypal_client_id'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('PayPal secret', __('messages.paypal_secret') . ':', ['class' => 'form-label']) }}
            {{ Form::text('paypal_secret', $credentials['paypal_secret'] ?? null, ['placeholder' => __('messages.paypal_secret'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
</div>
<div class="form-group col-md-12 mb-5 text-end">
    <div class="form-group">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary btn-save me-3']) }}
    </div>
</div>
{{ Form::close() }}
