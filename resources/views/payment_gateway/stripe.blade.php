{{ Form::open(['route' => 'payment-gateways.store', 'class' => '']) }}
<div class="row mb-5">
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('Stripe key', __('messages.stripe_key') . ':', ['class' => 'form-label']) }}
            {{ Form::text('stripe_key', $credentials['stripe_key'] ?? null, ['placeholder' => __('messages.stripe_key'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('Stripe secret', __('messages.stripe_secret') . ':', ['class' => 'form-label']) }}
            {{ Form::text('stripe_secret', $credentials['stripe_secret'] ?? null, ['placeholder' => __('messages.stripe_secret'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
</div>
<div class="form-group col-md-12 mb-5 text-end">
    <div class="form-group">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary btn-save me-3']) }}
    </div>
</div>
{{ Form::close() }}
