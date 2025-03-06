<div class="row gx-10 mb-5">
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
                {{ Form::select('patient_id', $data['patients'], null, ['class' => 'form-select', 'required', 'id' => 'editOpdPatientId', 'placeholder' => __('messages.document.select_patient'), 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label']) }}
                {{ Form::select('case_id', [null], null, ['class' => 'form-select', 'required', 'id' => 'editOpdCaseId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
                {{ Form::hidden('patient_case_id', !empty($opdPatientDepartment->patientCase) ? $opdPatientDepartment->patientCase->case_id : '', ['class' => 'patientCaseId']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('opd_number', __('messages.opd_patient.opd_number') . ':', ['class' => 'form-label']) }}
                {{ Form::text('opd_number', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('messages.opd_patient.opd_number')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', null, ['placeholder' => __('messages.ipd_patient.height'), 'class' => 'form-control', 'max' => '7', 'step' => '.01']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', null, ['placeholder' => __('messages.ipd_patient.weight'), 'class' => 'form-control', 'max' => '200', 'step' => '.01']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp') . ':', ['class' => 'form-label']) }}
                {{ Form::text('bp', null, ['placeholder' => __('messages.ipd_patient.bp'), 'class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('appointment_date', __('messages.opd_patient.appointment_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('appointment_date', null, ['placeholder' => __('messages.opd_patient.appointment_date'), 'class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'editOpdAppointmentDate', 'autocomplete' => 'off', 'required']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('doctor_id', $data['doctors'], null, ['class' => 'form-select', 'required', 'id' => 'editOpdDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                <div class="form-group">
                    {{ Form::label('standard_charge', __('messages.doctor_opd_charge.standard_charge') . ':', ['class' => 'form-label']) }}
                    <div class="input-group">
                        {{ Form::text('standard_charge', null, ['class' => 'form-control price-input', 'id' => 'editOpdStandardCharge', 'required', 'placeholder' => __('messages.doctor_opd_charge.standard_charge')]) }}
                        <div class="input-group-text border-0"><a><span>{{ getCurrencySymbol() }}</span></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('payment_mode', __('messages.ipd_payments.payment_mode') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('payment_mode', $data['paymentMode'], null, ['class' => 'form-select', 'required', 'id' => 'editOpdPaymentMode', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.payment.payment')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms', __('messages.ipd_patient.symptoms') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes', __('messages.ipd_patient.notes') . ':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4, 'placeholder' => __('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
    @if (count($customField) >= 0)
        @foreach ($customField as $field)
        @php
            $field_values = explode(',',$field['values']);
            $dateType = ($field['field_type'] == 6) ? 'customFieldDate' : 'customFieldDateTime';
            $field_type = \App\Models\AddCustomFields::FIELD_TYPE_ARR[$field['field_type']];
            $fieldName = 'field'.$field['id'];
            $fieldData = isset($opdPatientDepartment->custom_field[$fieldName]) ? $opdPatientDepartment->custom_field[$fieldName] : null;
            // $datevalue =
        @endphp
        <div class="form-group col-sm-{{$field['grid']}} mb-5 text-capitalize">
            {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => 'form-label']) }}
            @if ($field['is_required'] == 1)
                <span class="required"></span>
            @endif
            @if ($field_type == 'date' || $field_type == 'date & Time' )
                {{ Form::text($fieldName, ($fieldData == 'y-m-d' ? $fieldData : null), ['id'=>$dateType,'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'autocomplete' => 'off','placeholder'=>$field['field_name'],$field['is_required'] == 1 ? 'required' : '']) }}
            @elseif ($field_type == 'toggle')
                <div class="form-check form-switch form-check-custom">
                    <input class="form-check-input w-35px h-20px is-active" name={{$fieldName}} type="checkbox" value="1"
                        tabindex="8" {{ $fieldData == 0 ? '' : 'checked'}}>
                </div>
            @elseif ($field_type == 'select')
                {{ Form::select($fieldName,$field_values, $fieldData, ['class' => 'form-select','placeholder' => $field['field_name'], 'data-control' => 'select2',$field['is_required'] == 1 ? 'required' : '' ]) }}
            @elseif ($field_type == 'multiSelect')
                {{ Form::select($fieldName.'[]', $field_values, $fieldData, ['class' => 'form-select','placeholder' => $field['field_name'], 'data-control' => 'select2', 'multiple' => true,$field['is_required'] == 1 ? 'required' : '']) }}
            @else
                {{ Form::$field_type($fieldName, $fieldData, ['class' => 'form-control','placeholder' => $field['field_name'],$field['is_required'] == 1 ? 'required' : '','rows' => $field_type == 'textarea' ?  4 : ''  ]) }}
            @endif
        </div>
        @endforeach
    @endif
    <div class="col-md-3">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}
                <br>
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px" name="is_old_patient" type="checkbox" value="1"
                        {{ $opdPatientDepartment->is_old_patient ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => getCurrentLoginUserLanguageName() == 'ar' ? 'btn btn-primary ms-3' : 'btn btn-primary me-3', 'id' => 'btnEditOpdSave']) }}
    <a href="{!! route('opd.patient.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
