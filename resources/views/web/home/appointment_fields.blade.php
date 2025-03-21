<div class="d-lg-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-3">{{ __('messages.web_appointment.make_an_appointment') }}</h2>
    <div class="d-flex align-items-center mb-3">
        <div class="form-check d-flex align-items-center mb-0">
            {{ Form::radio('patient_type', '1', true, ['class' => 'form-check-input new-patient-radio', 'id' => 'newPatient1', 'checked']) }}
            <label class="form-check-label {{ App::getLocale() == 'ar' ? 'me-5' : 'ms-3' }}" for="newPatient1">
                {{ __('messages.new_patient') }}
            </label>
        </div>
        <div class="form-check {{ App::getLocale() == 'ar' ? 'me-4' : 'ms-4' }} d-flex align-items-center mb-0">
            {{ Form::radio('patient_type', '2', false, ['class' => 'form-check-input old-patient-radio', 'id' => 'oldPatient1']) }}

            <label class="form-check-label {{ App::getLocale() == 'ar' ? 'me-5' : 'ms-3' }}" for="oldPatient1">
                {{ __('messages.old_patient') }}
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 old-patient d-none">
        <div class="appointment-form__input-block">
            <label for="patient_name" class="form-label">{{ __('messages.appointment.patient_name') }} :<span
                    class="required">*</span></label>
            {{ Form::text('patient_name', null, ['class' => 'form-control', 'id' => 'patientName', 'autocomplete' => 'off', 'required', 'disabled']) }}
            {{ Form::hidden('patient_id', null, ['id' => 'patient']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6 first-name-div">
        <div class="appointment-form__input-block">
            <label for="firstName" class="form-label">{{ __('messages.user.first_name') }} :<span
                    class="required">*</span></label>
            {{ Form::text('first_name', null, ['class' => 'form-control ', 'placeholder' => Lang::get('messages.web_appointment.enter_your_first_name'), 'autocomplete' => 'off', 'required', 'id' => 'firstName']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6 last-name-div">
        <div class="appointment-form__input-block">
            <label for="lastName" class="form-label">{{ __('messages.user.last_name') }} :<span
                    class="required">*</span></label>
            {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => Lang::get('messages.web_appointment.enter_your_last_name'), 'autocomplete' => 'off', 'required', 'id' => 'lastName']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6 gender-div">
        <div class="appointment-form__input-block">
            <label for="exampleFormControlInput1" class="form-label">{{ __('messages.user.gender') }} :<span
                    class="required">*</span></label>
            <div class="grp-radio d-flex align-items-center">
                <div class="form-check d-flex align-items-center mb-0">
                    {{ Form::radio('gender', '0', true, ['class' => 'form-check-input ms-3', 'id' => 'radioMale']) }}
                    <label class="form-check-label {{ App::getLocale() == 'ar' ? 'me-5' : 'ms-3' }}"
                        for="radioMale">{{ __('messages.user.male') }}</label>
                </div>
                <div class="form-check ms-4 d-flex align-items-center mb-0">
                    {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'id' => 'radioFemale']) }}
                    <label class="form-check-label {{ App::getLocale() == 'ar' ? 'me-5' : 'ms-3' }}"
                        for="radioFemale">{{ __('messages.user.female') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 patient-email-div">
        <div class="appointment-form__input-block">
            <label for="email" class="form-label">{{ __('messages.user.email') }} :
                <span class="required">*</span>
            </label>
            {{ Form::email('email', null, ['class' => 'form-control old-patient-email', 'placeholder' => Lang::get('messages.web_contact.enter_your_email'), 'autocomplete' => 'off', 'required']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6 password-div">
        <div class="appointment-form__input-block">
            <label for="password" class="form-label">{{ __('messages.user.password') }} :<span
                    class="required">*</span></label>
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => Lang::get('messages.web_appointment.enter_your_password'), 'required', 'min' => '6', 'max' => '10', 'id' => 'password']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6 confirm-password-div">
        <div class="appointment-form__input-block">
            <label for="confirmPassword" class="form-label">{{ __('messages.user.password_confirmation') }} :<span
                    class="required">*</span></label>
            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => Lang::get('messages.web_appointment.enter_confirm_password'), 'required', 'min' => '6', 'max' => '10', 'id' => 'confirmPassword']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="appointment-form__input-block">
            <label for="selectDepartment" class="form-label">{{ __('messages.appointment.doctor_department') }} :<span
                    class="required">*</span></label>

            {{ Form::select('department_id', $departments, null, ['id' => 'webDepartmentId', 'placeholder' => Lang::get('messages.web_appointment.select_department')]) }}
        </div>
        {{ Form::hidden('doctors', isset(session()->get('data')['doctorId']) ? session()->get('data')['doctorId'] : null, ['id' => 'doctor']) }}
        {{ Form::hidden('apdate', isset(session()->get('data')['appointmentDate']) ? session()->get('data')['appointmentDate'] : null, ['id' => 'appointmentDate']) }}
        {{--        <input type="hidden" id="doctor" value="{"> --}}
        {{--        <input type="hidden" id="appointmentDate" value="{{$data['appointmentDate']}}"> --}}
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="appointment-form__input-block">
            <label for="selectDoctor" class="form-label">{{ __('messages.appointment.doctor') }} :<span
                    class="required">*</span></label>
            {{ Form::select('doctor_id', $doctors, isset(session()->get('data')['doctorId']) ? session()->get('data')['doctorId'] : null, ['autocomplete' => 'off', 'id' => 'appointmentDoctorId', 'placeholder' => __('messages.web_appointment.select_doctor')]) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="appointment-form__input-block">
            <label for="date" class="form-label">{{ __('messages.investigation_report.date') }} :
                <span class="required">*</span>
            </label>
            {{ Form::text('opd_date', null, ['class' => 'form-control opdDate', 'autocomplete' => 'off', 'id' => 'opdDate', 'required']) }}
        </div>
    </div>
    <div class="col-lg-6 web-appointment-charge d-none">
        <div class="appointment-form__input-block">
            <label for="appointment_charge" class="form-label">{{ __('messages.doctor.appointment_charge') }} :</label>
            {{ Form::text('appointment_charge', null, ['class' => 'form-control', 'placeholder' => __('messages.doctor.appointment_charge'), 'id' => 'appointmentCharge', 'readonly' => 'readonly']) }}
        </div>
    </div>
    <div class="col-lg-6 paymentType d-none">
        <div class="appointment-form__input-block">
            <label for="payment_mode" class="form-label">{{ __('messages.ipd_payments.payment_mode') }} :<span
                    class="required paymentMode d-none">*</span></label>
            {{ Form::select('payment_mode', getAppointmentPaymentTypes(), null, ['class' => 'selectized appointmentPaymentMode', 'autocomplete' => 'off', 'id' => 'appointmentPaymentModeId', 'placeholder' => __('messages.ipd_payments.payment_mode')]) }}
        </div>
    </div>
    <div class="col-lg-12">
        <div class="appointment-form__input-block">
            <label for="description" class="form-label">{{ __('messages.appointment.description') }} :</label>
            {{ Form::textarea('problem', null, ['class' => 'form-control form-textarea', 'placeholder' => Lang::get('messages.web_appointment.enter_description'), 'autocomplete' => 'off', 'rows' => 4]) }}
        </div>
    </div>
    @if (count($customField) >= 0)
        @foreach ($customField as $field)
            @php
                $field_values = explode(',', $field['values']);
                $dateType = $field['field_type'] == 6 ? 'customFieldDate' : 'customFieldDateTime';
                $field_type = \App\Models\AddCustomFields::FIELD_TYPE_ARR[$field['field_type']];
                $fieldName = 'field' . $field['id'];
                $fieldData = isset($appointment->custom_field[$fieldName])
                    ? $appointment->custom_field[$fieldName]
                    : null;
            @endphp
            <div class="appointment-form__input-block col-sm-{{ $field['grid'] }} mb-5 text-capitalize">
                {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => $field['is_required'] == 1 ? 'form-label dynamic-field' : 'form-label']) }}
                @if ($field['is_required'] == 1)
                    <span class="required">*</span>
                @endif
                @if ($field_type == 'date' || $field_type == 'date & Time')
                    {{ Form::text($fieldName, $fieldData, ['id' => $dateType, 'class' => $field['is_required'] == 1 ? 'date-time form-control dynamic-field' : 'date-time bg-white form-control', 'autocomplete' => 'off', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '', 'readolny' => 'readonly']) }}
                @elseif ($field_type == 'toggle')
                    <div class="form-check form-switch">
                        <input style="height: 25px;width:40px"
                            class="form-check-input front-appointment-switch is-active {{ $field['is_required'] == 1 ? 'dynamic-field' : '' }}"
                            name={{ $fieldName }} type="checkbox" value="1" tabindex="8"
                            {{ $fieldData == 0 ? '' : 'checked' }}>
                    </div>
                @elseif ($field_type == 'select')
                    {{ Form::select($fieldName, $field_values, isset($fieldData) ? $fieldData : null, ['class' => $field['is_required'] == 1 ? 'dynamic-field custom-field-select' : 'custom-field-select', 'id' => 'customFieldSelect', 'placeholder' => $field['field_name']]) }}
                @elseif ($field_type == 'multiSelect')
                    {{ Form::select($fieldName . '[]', $field_values, isset($fieldData) ? $fieldData : null, ['class' => $field['is_required'] == 1 ? 'appointment-multi-select dynamic-field custom-field-multi-select' : 'appointment-multi-select custom-field-multi-select', 'id' => 'customFieldMultiSelect', 'multiple', 'placeholder' => $field['field_name']]) }}
                @else
                    {{ Form::$field_type($fieldName, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-control dynamic-field' : 'form-control', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '', 'rows' => $field_type == 'textarea' ? 4 : '']) }}
                @endif
            </div>
        @endforeach
    @endif
    <div class="form-group col-sm-12 appointment-slot {{ App::getLocale() == 'ar' ? 'text-end' : 'text-start' }}">
        <div class="doctor-schedule" style="display: none">
            <i class="fas fa-calendar-alt"></i>
            <span class="day-name"></span>
            <span class="schedule-time"></span>
        </div>
        <strong class="error-message" style="display: none"></strong>
        <div class="slot-heading mb-4">
            <strong class="available-slot-heading"
                style="display: none">{{ __('messages.appointment.available_slot') }}:<span
                    class="required">*</span></strong>
        </div>
        <div class="row">
            <div class="available-slot form-group col-sm-10">
            </div>
        </div>
        <div class="color-information d-none" align="right" style="display: none">
            <span><i class="fa fa-circle fa-xs" aria-hidden="true"> </i>
                {{ __('messages.bed.not_available') }}</span>
        </div>
    </div>
    <div class="col-lg-12 text-center mt-4">
        <button type="submit" class="btn btn-primary custom-btn-lg"
            id="webAppointmentBtnSave">{{ __('messages.common.save') }}</button>
    </div>
</div>
