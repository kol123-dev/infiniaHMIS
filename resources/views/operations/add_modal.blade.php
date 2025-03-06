<div id="add_operations_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.common.new') }}
                    {{ __('messages.operation.operation') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addOperationForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="operationErrorsBox"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-5">
                            {{ Form::label('operation_category_id', __('messages.operation_category.operation_category') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::select('operation_category_id', $operation_categories, null, ['class' => 'form-select', 'id' => 'operationCategoryId', 'placeholder' => __('messages.medicine.select_category')]) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-5">
                            {{ Form::label('name', __('messages.user.name') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::text('name', null, ['class' => 'form-control','id' => 'operationName','placeholder'=>__('messages.user.name')]) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'operationSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" aria-label="Close" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
