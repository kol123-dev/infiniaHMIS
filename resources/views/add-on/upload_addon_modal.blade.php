<div class="modal fade" id="uploadAddOnModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.addon.upload_addon') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addOnForm', 'files' => true, 'class' => 'addOnForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="addonErrorsBox"></div>

                <div class="row">

                    <div class="form-group mb-5">
                        {{ Form::label('file', __('messages.addon.addon') . ':', ['class' => 'form-label required']) }}
                        <br>
                        {{ Form::file('file', [
                            'id' => 'addOnDocumentZip',
                            'class' => 'form-control',
                            'accept' => '.zip,application/zip',
                            'required',
                        ]) }}
                    </div>
                    <div class="modal-footer p-0">
                        {{ Form::button(__('messages.common.upload'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'addOnBtnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                        <button type="button" aria-label="Close" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
