<div id="add_advanced_payments_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.advanced_payment.new_advanced_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'addAdvancedPaymentForm']) }}
            {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="validationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('patient_id', __('messages.advanced_payment.patient').(':'), ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('patient_id', $patients, null, ['class' => 'form-select', 'id' => 'advancePaymentPatientId','placeholder' => __('messages.document.select_patient'), 'required','data-control' => 'select2']) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('receipt_no', __('messages.advanced_payment.receipt_no').(':'),['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('receipt_no', null, ['class' => 'form-control','required','readonly','id'=>'receiptNoId', 'placeholder' => __('messages.advanced_payment.receipt_no')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('amount', __('messages.advanced_payment.amount').(':'),['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('amount', null, ['class' => 'form-control price-input','required', 'onkeyup' => 'if (parseInt(this.value.replace(/[^\d.]/g, "")) > 1000000) this.value = this.value.slice(0, -1)','placeholder'=>__('messages.advanced_payment.amount')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('date', __('messages.advanced_payment.date').(':'),['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('date', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'),'required','id' => 'advancedPaymentDate', 'autocomplete' => 'off','placeholder'=>__('messages.advanced_payment.date')]) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'advancedPaymentSave','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
