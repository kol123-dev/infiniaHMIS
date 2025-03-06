<div id="add_charges_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.charge.new_charge') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addChargesForm']) }}
            {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="chargesErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('charge_type', __('messages.charge_category.charge_type') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('charge_type', $chargeTypes, null, ['class' => 'form-select', 'required', 'id' => 'chargeTypeId', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.charge_category.charge_type')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('charge_category_id', __('messages.charge.charge_category') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {!! Form::select('charge_category_id', isset($chargeCategories) ? nl2br(e($chargeCategories)) : [], null, [
                            'class' => 'form-select',
                            'required',
                            'id' => 'chargeCategoryId',
                            'placeholder' => __('messages.pathology_category.select_charge_category'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('code', __('messages.charge.code') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('code', null, ['class' => 'form-control','required','placeholder'=>__('messages.charge.code')]) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('standard_charge', __('messages.charge.standard_charge') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('standard_charge', null, ['class' => 'form-control price-input','required','placeholder'=>__('messages.charge.standard_charge')]) }}
                    </div>

                    <div class="form-group col-sm-12">
                        {{ Form::label('description', __('messages.birth_report.description').(':'), ['class' => 'form-label']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.birth_report.description')]) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'chargesSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" aria-label="Close" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
