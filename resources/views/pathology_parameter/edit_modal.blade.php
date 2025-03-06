<div id="editPathologyParametersModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.new_change.edit_parameter') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'editPathologyParameterForm', 'method' => 'patch']) }}
            <div class="modal-body">
                <div class="alert alert-danger display-none hide" id="editParameterValidationErrorsBox"></div>
                {{ Form::hidden('pathologyParameterId', null, ['id' => 'pathologyParameterId']) }}
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('parameter_name', __('messages.pathology_category.name') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('parameter_name', '', ['id' => 'editPathologyParameterName', 'class' => 'form-control', 'required','placeholder' => __('messages.pathology_category.name')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('reference_range', __('messages.new_change.reference_range') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('reference_range', '', ['id' => 'editParameterRange', 'class' => 'form-control', 'required','placeholder' => __('messages.new_change.reference_range')]) }}
                    </div>
                    <div class="form-group mb-5">
                        {{ Form::label('unit_id', __('messages.item.unit') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('unit_id', $unit, null, ['class' => 'form-select edit-unit', 'required', 'placeholder' => __('messages.new_change.select_unit'), 'required', 'id' => 'editPathologyUnitId']) }}
                    </div>
                    <div class="form-group mb-5">
                        {{ Form::label('description', __('messages.bed.description') . ':', ['class' => 'form-label']) }}
                        {{ Form::textarea('description', '', ['id' => 'editParameterDescription', 'class' => 'form-control', 'rows' => 4, 'cols' => 3,'placeholder' => __('messages.bed.description')]) }}
                    </div>
                </div>
                <div class="modal-footer p-0">
                    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'editPathologyParameterSaveBtn', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" aria-label="Close" class="btn btn-secondary ms-2"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
