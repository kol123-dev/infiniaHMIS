<div class="row">
    <!-- Patient Name Field -->
    @if (Auth::user()->hasRole('Patient'))
        <input type="hidden" name="patient_id" value="{{ Auth::user()->owner_id }}">
    @else
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('patient_name', __('messages.case.patient') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('patient_id', $patients, null, ['class' => 'form-select', 'required', 'id' => 'appointmentPatientId', 'placeholder' => __('messages.document.select_patient'), 'data-control' => 'select2']) }}
        </div>
    @endif
    <!-- Department Name Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('department_name', __('messages.appointment.doctor_department') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('department_id', $departments, null, ['class' => 'form-select', 'required', 'id' => 'appointmentDepartmentId', 'placeholder' => __('messages.web_appointment.select_department'), 'data-control' => 'select2']) }}
    </div>
    <!-- Doctor Name Field -->
    <div class="form-group col-sm-6 mb-5">
        {{ Form::label('doctor_name', __('messages.case.doctor') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('doctor_id', isset($doctors) ? $doctors : [], null, ['class' => 'form-select', 'required', 'id' => 'appointmentDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2']) }}
    </div>

    @if (!Auth::user()->hasRole('Patient'))
        <!-- Date Field -->
        <div class="form-group col-sm-6 mb-5 ">
            {{ Form::label('opd_date', __('messages.appointment.date') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('opd_date', isset($appointment) ? $appointment->opd_date->format('Y-m-d') : null, ['id' => 'appointmentOpdDate', 'class' => getLoggedInUser()->thememode ? 'bg-light opdDate form-control' : 'bg-white opdDate form-control', 'required', 'autocomplete' => 'off', 'placeholder' => __('messages.appointment.date')]) }}
        </div>

        {{-- Appointment Charge --}}
        <div class="form-group col-sm-6 mb-5 appointmentCharge d-none">
            {{ Form::label('appointment_charge', __('messages.doctor.appointment_charge') . ':', ['class' => 'form-label']) }}
            {{ Form::text('appointment_charge', isset($doctorCharge) ? $doctorCharge : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('messages.doctor.appointment_charge'), 'id' => 'appointmentCharge', 'readonly' => 'readonly']) }}
        </div>
        {{-- Appointment Charge --}}
        @if (isset($appointment))
            <div class="form-group col-sm-6 mb-5 paymentType d-none">
                {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
                <span class="required d-none paymentMode"></span>
                {{ Form::select('payment_mode', getEditAppointmentPaymentTypes(), $appointment->payment_type ?? null, ['class' => 'form-select  select2Selector', 'id' => 'appointmentPaymentModeId', 'placeholder' => __('messages.ipd_payments.payment_mode')]) }}
            </div>
        @else
            <div class="form-group col-sm-6 mb-5 paymentType d-none">
                {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
                <span class="required d-none paymentMode"></span>
                {{ Form::select('payment_mode', getAppointmentPaymentTypes(), null, ['class' => 'form-select  select2Selector', 'id' => 'appointmentPaymentModeId', 'placeholder' => __('messages.ipd_payments.payment_mode')]) }}
            </div>
        @endif

        <!-- Notes Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('problem', __('messages.appointment.description') . ':', ['class' => 'form-label']) }}
            {{ Form::textarea('problem', null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => __('messages.appointment.description')]) }}
        </div>
        <div class="form-group col-sm-6 mb-5">
            <div class="form-group col-sm-12 mb-5 editSlotTime d-none">
                {{ Form::label('timeslot', __('messages.appointment.time') . ':', ['class' => 'form-label']) }}
                {{ Form::text('timeslot', isset($appointment) ? $appointment->opd_date->format('H:i') : null, ['class' => 'form-control', 'id' => 'editTimeSlot', 'disabled' => 'disabled']) }}
            </div>
            {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label']) }}
            <br>
            <div class="form-check form-switch">
                <input class="form-check-input w-35px h-20px" name="status" type="checkbox" value="1"
                    {{ isset($appointment) ? ($appointment->is_completed == 0 ? '' : 'checked') : 'checked' }}>
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
                <div class="form-group col-sm-{{ $field['grid'] }} mb-5 text-capitalize">
                    {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => $field['is_required'] == 1 ? 'form-label dynamic-field' : 'form-label']) }}
                    @if ($field['is_required'] == 1)
                        <span class="required"></span>
                    @endif
                    @if ($field_type == 'date' || $field_type == 'date & Time')
                        {{ Form::text($fieldName, $fieldData == 'y-m-d' ? $fieldData : null, ['id' => $dateType, 'class' => getLoggedInUser()->thememode || $field['is_required'] == 1 ? 'bg-light form-control dynamic-field' : 'bg-white form-control', 'autocomplete' => 'off', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '']) }}
                    @elseif ($field_type == 'toggle')
                        <div class="form-check form-switch form-check-custom">
                            <input
                                class="form-check-input w-35px h-20px is-active {{ $field['is_required'] == 1 ? 'dynamic-field' : '' }}"
                                name={{ $fieldName }} type="checkbox" value="1" tabindex="8"
                                {{ $fieldData == 0 ? '' : 'checked' }}>
                        </div>
                    @elseif ($field_type == 'select')
                        {{ Form::select($fieldName, $field_values, isset($fieldData) ? $fieldData : null, ['class' => $field['is_required'] == 1 ? 'form-select dynamic-field' : 'form-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2', $field['is_required'] == 1 ? 'required' : '']) }}
                    @elseif ($field_type == 'multiSelect')
                        {{ Form::select($fieldName . '[]', $field_values, isset($fieldData) ? $fieldData : 0, ['class' => $field['is_required'] == 1 ? 'form-select appointment-multi-select dynamic-field' : 'form-select appointment-multi-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2', 'multiple' => true, $field['is_required'] == 1 ? 'required' : '']) }}
                    @else
                        {{ Form::$field_type($fieldName, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-control dynamic-field' : 'form-control', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '', 'rows' => $field_type == 'textarea' ? 4 : '']) }}
                    @endif
                </div>
            @endforeach
        @endif
        <div class=" col-sm-6 mb-5 offset-sm-6">
        </div>
        <div class="form-group col-sm-6 mb-5">
            <div class="doctor-schedule" style="display: none">
                <i class="fas fa-calendar-alt"></i>
                <span class="day-name"></span>
                <span class="schedule-time"></span>
            </div>
            <strong class="error-message" style="display: none"></strong>
            <div class="slot-heading">
                <h3 class="available-slot-heading" style="display: none">
                    {{ __('messages.appointment.available_slot') . ':' }}<span class="required"></span></h3>
            </div>
            <div class="row">
                <div class="available-slot form-group col-sm-12">
                </div>
            </div>
            <div align="right" style="display: none">
                <span><i class="fa fa-circle color-information" aria-hidden="true"> </i>
                    {{ __('messages.appointment.no_available') }}</span>
            </div>
        </div>
    @endif
    @if (Auth::user()->hasRole('Patient'))

        {{-- Appointment Charge --}}
        <div class="form-group col-sm-6 mb-5 appointmentCharge d-none">
            {{ Form::label('appointment_charge', __('messages.doctor.appointment_charge') . ':', ['class' => 'form-label']) }}
            {{ Form::text('appointment_charge', isset($doctorCharge) ? $doctorCharge : null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('messages.doctor.appointment_charge'), 'id' => 'appointmentCharge', 'readonly' => 'readonly']) }}
        </div>

        {{-- Appointment Charge --}}
        <div class="form-group col-sm-6 mb-5 paymentType d-none">
            {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
            <span class="required d-none paymentMode"></span>
            {{ Form::select('payment_mode', getAppointmentPaymentTypes(), null, ['class' => 'form-select  select2Selector', 'id' => 'appointmentPaymentModeId', 'placeholder' => __('messages.ipd_payments.payment_mode')]) }}
        </div>

        <!-- Date Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('opd_date', __('messages.appointment.date') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::text('opd_date', null, ['id' => 'patientAppointmentOpdDate', 'class' => getLoggedInUser()->thememode ? 'bg-light opdDate form-control' : 'bg-white opdDate form-control', 'required', 'autocomplete' => 'off', 'placeholder' => __('messages.appointment.date')]) }}
        </div>

        <!-- Notes Field -->
        <div class="form-group col-sm-6 mb-5">
            {{ Form::label('problem', __('messages.appointment.description') . ':', ['class' => 'form-label']) }}
            {{ Form::textarea('problem', null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => __('messages.appointment.description')]) }}
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
                <div class="form-group col-sm-{{ $field['grid'] }} mb-5 text-capitalize">
                    {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => $field['is_required'] == 1 ? 'form-label dynamic-field' : 'form-label']) }}
                    @if ($field['is_required'] == 1)
                        <span class="required"></span>
                    @endif
                    @if ($field_type == 'date' || $field_type == 'date & Time')
                        {{ Form::text($fieldName, $fieldData, ['id' => $dateType, 'class' => getLoggedInUser()->thememode || $field['is_required'] == 1 ? 'bg-light form-control dynamic-field' : 'bg-white form-control', 'autocomplete' => 'off', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '']) }}
                    @elseif ($field_type == 'toggle')
                        <div class="form-check form-switch form-check-custom">
                            <input
                                class="form-check-input w-35px h-20px is-active {{ $field['is_required'] == 1 ? 'dynamic-field' : '' }}"
                                name={{ $fieldName }} type="checkbox" value="1" tabindex="8"
                                {{ $fieldData == 0 ? '' : 'checked' }}>
                        </div>
                    @elseif ($field_type == 'select')
                        {{ Form::select($fieldName, $field_values, isset($fieldData) ? $fieldData : null, ['class' => $field['is_required'] == 1 ? 'form-select dynamic-field' : 'form-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2', $field['is_required'] == 1 ? 'required' : '']) }}
                    @elseif ($field_type == 'multiSelect')
                        {{ Form::select($fieldName . '[]', $field_values, isset($fieldData) ? $fieldData : 0, ['class' => $field['is_required'] == 1 ? 'form-select appointment-multi-select dynamic-field' : 'form-select appointment-multi-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2', 'multiple' => true, $field['is_required'] == 1 ? 'required' : '']) }}
                    @else
                        {{ Form::$field_type($fieldName, $fieldData, ['class' => $field['is_required'] == 1 ? 'form-control dynamic-field' : 'form-control', 'placeholder' => $field['field_name'], $field['is_required'] == 1 ? 'required' : '', 'rows' => $field_type == 'textarea' ? 4 : '']) }}
                    @endif
                </div>
            @endforeach
        @endif
        <div class=" col-sm-6 mb-5 offset-sm-6">
        </div>
        <div class="form-group col-sm-6 available-slot-div">
            <div class="doctor-schedule" style="display: none">
                <i class="fas fa-calendar-alt"></i>
                <span class="day-name"></span>
                <span class="schedule-time"></span>
            </div>
            <strong class="error-message" style="display: none"></strong>
            <div class="slot-heading">
                <strong class="available-slot-heading"
                    style="display: none">{{ __('messages.appointment.available_slot') . ':' }}<span class="required"></span></strong>
            </div>
            <div class="row">
                <div class="available-slot form-group col-sm-10">
                </div>
            </div>
            <div class="color-information" align="right" style="display: none">
                <span><i class="fa fa-circle fa-xs" aria-hidden="true"> </i>
                    {{ __('messages.appointment.no_available') }}</span>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <!-- Submit Field -->
    <div class="form-group col-sm-12 d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => (getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary ms-3' : 'btn btn-primary me-3'), 'id' => 'saveAppointment','data-turbo' => 'false']) }}
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
