<div id="addPatientModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.patient.new_patient') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'addPatientForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="patientErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('first_name', __('messages.user.first_name').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('first_name', null, ['placeholder' => __('messages.user.first_name'),'class' => 'form-control', 'required', 'id' => 'patientFirstName','tabindex' => '1']) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('last_name', __('messages.user.last_name').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('last_name', null, ['placeholder' => __('messages.user.last_name'),'class' => 'form-control', 'required', 'tabindex' => '2']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('email', null, ['placeholder' => __('messages.user.email'),'class' => 'form-control','required']) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
                        <span class="required"></span><br>
                        {{ Form::tel('phone', getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'patientPhoneNumber', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5']) }}
                        {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
                        <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
                        <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('gender', __('messages.user.gender').':', ['class' => 'form-label']) }}
                        <span
                            class="required"></span> &nbsp;<br>
                        <span class="is-valid">
                            <label class="form-label">{{ __('messages.user.male') }}</label>&nbsp;&nbsp;
                            {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6','id'=>'patientMale']) }} &nbsp;
                            <label class="form-label">{{ __('messages.user.female') }}</label>
                            {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7','id'=>'patientFemale']) }}
                        </span>
                    </div>
                    <div class="form-group col-sm-6 mb-5 d-flex flex-column">
                        {{ Form::label('status', __('messages.common.status').':', ['class' => 'form-label']) }}
                        <div class="form-check form-switch form-check-custom">
                            <input class="form-check-input w-35px h-20px is-active {{getCurrentLoginUserLanguageName()== 'ar' ? 'float-end' : 'float-start'}}" name="status" type="checkbox" value="1"
                                tabindex="8" checked>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('password', __('messages.user.password').':', ['class' => 'form-label']) }}<span class="required"></span>
                        {{ Form::password('password', ['placeholder' => __('messages.user.password'),'class' => 'form-control','required','min' => '6','max' => '10', 'tabindex' => '10']) }}
                    </div>
                    <div class="form-group col-sm-6 mb-5">
                        {{ Form::label('password_confirmation', __('messages.user.password_confirmation').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::password('password_confirmation', ['placeholder' => __('messages.user.password_confirmation'),'class' => 'form-control','required','min' => '6','max' => '10', 'tabindex' => '11']) }}
                    </div>
                </div>
                <div class="modal-footer p-0">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary m-0','id'=>'patientBtnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" aria-label="Close" class="btn btn-secondary"
                            data-bs-dismiss="modal">{!! __('messages.common.cancel') !!}
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
