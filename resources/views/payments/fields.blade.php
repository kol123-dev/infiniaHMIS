<div class="row">
    <div class="col-sm-6 mb-5">
        {{ Form::label('account_id', __('messages.payment.account') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('account_id', $accounts, null, ['class' => 'form-select', 'required', 'id' => 'accountId', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.payment.account'), 'data-control' => 'select2']) }}
    </div>
    <div class="col-sm-6 mb-5">
        {{ Form::label('payment_date', __('messages.payment.payment_date') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('payment_date', null, ['id'=>'paymentDate', 'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'required','autocomplete' => 'off','placeholder'=>__('messages.payment.payment_date')]) }}
    </div>
    <div class="col-sm-6 mb-5">
        {{ Form::label('pay_to', __('messages.payment.pay_to') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('pay_to', null, ['class' => 'form-control', 'required','placeholder'=>__('messages.payment.pay_to')]) }}
    </div>
    <div class="col-sm-6 mb-5">
        {{ Form::label('amount', __('messages.payment.amount') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
        {{ Form::text('amount', null, ['class' => 'form-control price-input price', 'required','placeholder'=>__('messages.payment.amount')]) }}
    </div>
    <div class="col-sm-6 mb-5">
        {{ Form::label('description', __('messages.payment.description').(':'),['class' => 'form-label']) }}
        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.payment.description')]) }}
    </div>
</div>
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' =>getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary ms-3' : 'btn btn-primary me-3', 'id' => 'btnPaymentSave']) !!}
    <a href="{!! route('payments.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
