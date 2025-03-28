<div class="alert alert-danger d-none hide" id="editPatientErrorsBox"></div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('first_name', __('messages.user.first_name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('first_name', null, ['class' => 'form-control', 'required', 'tabindex' => '1','placeholder'=>__('messages.user.first_name')]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('last_name', __('messages.user.last_name').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('last_name', null, ['class' => 'form-control', 'required', 'tabindex' => '2','placeholder'=>__('messages.user.last_name')]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('email', null, ['class' => 'form-control', 'required', 'tabindex' => '3','id'=>'editPatientEmail','placeholder'=>__('messages.user.email')]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('dob', __('messages.user.dob').':', ['class' => 'form-label']) }}
            {{ Form::text('dob', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light patientBirthDate form-control' : 'bg-white patientBirthDate form-control'), 'id' => 'editPatientBirthDate', 'autocomplete' => 'off', 'tabindex' => '4','placeholder'=>__('messages.user.dob')]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mobile-overlapping  mb-5">
            {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
            <span class="required"></span><br>
            {{ Form::tel('phone', $patient->patientUser->phone ?? getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'editPatientPhoneNumber', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5']) }}
            {{ Form::hidden('prefix_code',null,['class'=>'prefix_code']) }}
            {{ Form::hidden('country_iso',null,['class'=>'country_iso']) }}
            <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{__('messages.valid')}}</span>
            <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('gender', __('messages.user.gender').':', ['class' => 'form-label']) }}
            <span
                    class="required"></span> &nbsp;<br>
            <span class="is-valid">
                <label class="form-label">{{ __('messages.user.male') }}</label>&nbsp;&nbsp;
                {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6','id'=>'editPatientMale']) }} &nbsp;
                <label class="form-label">{{ __('messages.user.female') }}</label>
                {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7','id'=>'editPatientFemale']) }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5 d-flex flex-column">
            {{ Form::label('status', __('messages.common.status').':', ['class' => 'form-label']) }}
            <div class="form-check form-switch form-check-custom">
                <input class="form-check-input w-35px h-20px is-active {{getCurrentLoginUserLanguageName()== 'ar' ? 'float-end' : 'float-start'}}" name="status" type="checkbox" value="1"
                       tabindex="8" {{ ($patient->patientUser->status === 1) ? 'checked' : '' }}>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('blood_group', __('messages.user.blood_group').':', ['class' => 'form-label']) }}
            {{ Form::select('blood_group', $bloodGroup, null, ['class' => 'form-select', 'id' => 'editPatientBloodGroup', 'placeholder' => __('messages.user.select_blood_group'), 'data-control' => 'select2', 'tabindex' => "9"]) }}
        </div>
    </div>
    @if (count($customField) >= 0)
        @foreach ($customField as $field)
        @php
            $field_values = explode(',',$field['values']);
            $dateType = ($field['field_type'] == 6) ? 'customFieldDate' : 'customFieldDateTime';
            $field_type = \App\Models\AddCustomFields::FIELD_TYPE_ARR[$field['field_type']];
            $fieldName = 'field'.$field['id'];
            $fieldData = isset($patient->custom_field[$fieldName ]) ? $patient->custom_field[$fieldName ] : null;

        @endphp
        <div class="form-group col-sm-{{$field['grid']}} mb-5 text-capitalize">
            {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => $field['is_required'] == 1 ? 'form-label dynamic-field' : 'form-label']) }}
            @if ($field['is_required'] == 1)
                <span class="required"></span>
            @endif
            @if ($field_type == 'date' || $field_type == 'date & Time' )
                {{ Form::text($fieldName, ($fieldData == 'y-m-d' ? $fieldData : null), ['id' => $dateType,'class' => getLoggedInUser()->thememode || $field['is_required'] == 1 ? 'bg-light form-control dynamic-field' : 'bg-white form-control',  'autocomplete' => 'off', 'placeholder' => $field['field_name'],$field['is_required'] == 1 ? 'required' : '']) }}
            @elseif ($field_type == 'toggle')
                <div class="form-check form-switch form-check-custom">
                    <input class="form-check-input w-35px h-20px is-active {{ $field['is_required'] == 1 ? 'dynamic-field' : ''}}" name={{$fieldName}} type="checkbox" value="1"
                        tabindex="8" {{ $fieldData == 0 ? '' : 'checked'}}>
                </div>
            @elseif ($field_type == 'select')
                {{ Form::select($fieldName, $field_values, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-select dynamic-field' : 'form-select','placeholder' => $field['field_name'], 'data-control' => 'select2',$field['is_required'] == 1 ? 'required' : '']) }}
            @elseif ($field_type == 'multiSelect')
                {{ Form::select($fieldName.'[]', $field_values, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-select patient-multi-select dynamic-field' : 'form-select patient-multi-select','placeholder' => $field['field_name'], 'data-control' => 'select2', 'multiple' => true,$field['is_required'] == 1 ? 'required' : '']) }}
            @else
                {{ Form::$field_type($fieldName, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-control dynamic-field' : 'form-control','placeholder' => $field['field_name'],$field['is_required'] == 1 ? 'required' : '','rows' => $field_type == 'textarea' ?  4 : ''  ]) }}
            @endif
        </div>
        @endforeach
    @endif

    <div class="form-group col-md-4">
        <div class="row2" io-image-input="true">
            {{ Form::label('image',__('messages.common.profile').(':'), ['class' => 'form-label']) }}
            <div class="d-block">
                <div class="image-picker">
                    <div class="image previewImage" id="editPatientPreviewImage"
                         style="background-image: url({{ isset($patient->patientUser->media[0]) ? $patient->patientUser->image_url : asset('assets/img/avatar.png') }})">
                        <span class="picker-edit rounded-circle text-gray-500 fs-small" title="{{ __('messages.common.profile') }}">
                            <label>
                                <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                <input type="file" id="editAccountantProfileImage" name="image"
                                       class="image-upload d-none profileImage editPatientImage"
                                       accept=".png, .jpg, .jpeg, .gif"/>
                                <input type="hidden" name="avatar_remove"/>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h5>{{ __('messages.user.address_details') }}</h5>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-5">
                {{ Form::label('address1', __('messages.user.address1').':', ['class' => 'form-label']) }}
                {{ Form::text('address1', $patient->address->address1 ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.address1')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-5">
                {{ Form::label('address2', __('messages.user.address2').':', ['class' => 'form-label']) }}
                {{ Form::text('address2', $patient->address->address2 ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.address2')]) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('city', __('messages.user.city').':', ['class' => 'form-label']) }}
                {{ Form::text('city', $patient->address->city ?? null, ['class' => 'form-control','placeholder'=>__('messages.user.city')]) }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-5">
                {{ Form::label('zip', __('messages.user.zip').':', ['class' => 'form-label']) }}
                {{ Form::text('zip', $patient->address->zip ?? null, ['class' => 'form-control', 'maxlength' => '6','minlength' => '6','placeholder'=>__('messages.user.zip')]) }}
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3 mb-5">
        <div class="col-md-12 mb-3">
            <h5>{{ __('messages.setting.social_details') }}</h5>
        </div>

        <!-- Facebook URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('facebook_url', __('messages.facebook_url').':', ['class' => 'form-label']) }}
            {{ Form::text('facebook_url', null, ['class' => 'form-control patientFacebookUrl','id'=>'editPatientFacebookUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.facebook_url')]) }}
        </div>

        <!-- Twitter URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('twitter_url', __('messages.twitter_url').':', ['class' => 'form-label']) }}
            {{ Form::text('twitter_url', null, ['class' => 'form-control patientTwitterUrl','id'=>'editPatientTwitterUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.twitter_url')]) }}
        </div>

        <!-- Instagram URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('instagram_url', __('messages.instagram_url').':', ['class' => 'form-label']) }}
            {{ Form::text('instagram_url', null, ['class' => 'form-control patientInstagramUrl', 'id'=>'editPatientInstagramUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.instagram_url')]) }}
        </div>

        <!-- LinkedIn URL Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('linkedIn_url', __('messages.linkedIn_url').':', ['class' => 'form-label']) }}
            {{ Form::text('linkedIn_url', null, ['class' => 'form-control patientLinkedInUrl','id'=>'editPatientLinkedInUrl', 'onkeypress' => 'return avoidSpace(event);','placeholder'=>__('messages.linkedIn_url')]) }}
        </div>

    </div>

    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-2','id' => 'editPatientSave']) }}
        <a href="{{ route('patients.index') }}"
           class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
    </div>
