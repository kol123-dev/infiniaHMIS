{{ Form::open(['route' => 'payment-gateways.store', 'class' => '']) }}
<div class="row mb-5">
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('razorpay key', __('messages.razorpay_key') . ':', ['class' => 'form-label']) }}
            {{ Form::text('razorpay_key', $credentials['razorpay_key'] ?? null, ['placeholder' => __('messages.razorpay_key'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
    <div class="form-group col-md-6 mb-5">
        <div class="form-group">
            {{ Form::label('razorpay secret', __('messages.razorpay_secret') . ':', ['class' => 'form-label']) }}
            {{ Form::text('razorpay_secret', $credentials['razorpay_secret'] ?? null, ['placeholder' => __('messages.razorpay_secret'), 'class' => 'form-control bg-white', 'id' => '', 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
</div>
<div class="form-group col-md-12 mb-5 text-end">
    <div class="form-group">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary btn-save me-3']) }}
    </div>
</div>
{{ Form::close() }}
