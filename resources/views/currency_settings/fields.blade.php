<!-- Currency Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency_name', __('messages.currency.currency_name') . ':') !!}
    {!! Form::text('currency_name', null, ['class' => 'form-control', 'placeholder' => __('messages.currency.currency_name')]) !!}
</div>

<!-- Currency Icon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency_icon', __('messages.currency.currency_icon') . ':') !!}
    {!! Form::text('currency_icon', null, ['class' => 'form-control', 'placeholder' => __('messages.currency.currency_icon')]) !!}
</div>

<!-- Currency Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('currency_code', __('messages.currency.currency_code') . ':') !!}
    {!! Form::text('currency_code', null, ['class' => 'form-control', 'placeholder' =>  __('messages.currency.currency_code')]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('currencySettings.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
</div>
