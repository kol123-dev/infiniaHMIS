<div id="generate_patient_card_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.patient_id_card.generate_patient_id_card') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['id' => 'addTemplateForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="AddTemplateErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('template_id', __('messages.patient_id_card.select_template') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('template_id', $templates, null, ['id' => 'templateId', 'class' => 'form-select io-select2 select_template_id', 'data-control' => 'select2', 'required', 'placeholder' => __('messages.patient_id_card.select_template')]) }}
                    </div>
                    <div class="col-md-12">
                        <div class="mb-5">
                            <label class="form-label required">
                                {{ __('messages.patient_id_card.select_type') . ':' }}
                            </label>
                            <span class="is-valid">
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <input class="form-check-input type_tem" type="radio" checked
                                                name="type" value="1" id="AllPatient">
                                            <label
                                                class="form-label {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-5' : 'me-5'}}">{{ __('messages.patient_id_card.all_patient') }}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <input class="form-check-input type_tem" type="radio" name="type"
                                                value="3" id="RemainingPatient">
                                            <label
                                                class="form-label">{{ __('messages.patient_id_card.remaining_patients') }}</label>
                                        </div>
                                    </div>
                                    <input class="form-check-input type_tem mt-3" type="radio" name="type"
                                        value="2" id="OnlyOnePatient">
                                    <label
                                        class="form-label mt-3">{{ __('messages.patient_id_card.one_patient') }}</label>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 patient_select mb-5 d-none">
                        {{ Form::label('patient_id', __('messages.document.select_patient') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('patient_id', $patients, null, ['class' => 'form-select select_patient_id', 'data-control' => 'select2', 'id' => 'PatientId', 'placeholder' => __('messages.document.select_patient')]) }}
                    </div>
                </div>
                <div class="modal-footer p-0">
                    {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'AddTemplateSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" aria-label="Close" class="btn btn-secondary"
                        data-bs-dismiss="modal">{!! __('messages.common.cancel') !!}
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
