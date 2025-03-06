<div class="row gx-10 mb-5">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('patient_id', __('messages.ipd_patient.patient_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('patient_id', $data['patients'], null, ['class' => 'form-select ipdPatientId', 'required', 'id' => 'ipdPatientId', 'placeholder' => __('messages.document.select_patient'), 'tabindex' => '1', 'data-control' => 'select2']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('case_id', __('messages.ipd_patient.case_id') . ':', ['class' => 'form-label required']) }}
                {{ Form::select('case_id', [null], null, ['class' => 'form-select ipdDepartmentCaseId', 'id' => 'ipdDepartmentCaseId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.cases')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('ipd_number', __('messages.ipd_patient.ipd_number') . ':', ['class' => 'form-label']) }}
                {{ Form::text('ipd_number', $data['ipdNumber'], ['class' => 'form-control', 'readonly', 'placeholder' => __('messages.ipd_patient.ipd_number')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('height', __('messages.ipd_patient.height') . ':', ['class' => 'form-label']) }}
                {{ Form::number('height', 0, ['class' => 'form-control ipdDepartmentFloatNumber', 'step' => '.01', 'tabindex' => '2', 'placeholder' => __('messages.ipd_patient.height')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('weight', __('messages.ipd_patient.weight') . ':', ['class' => 'form-label']) }}
                {{ Form::number('weight', 0, ['placeholder' => __('messages.ipd_patient.weight'),'class' => 'form-control ipdDepartmentFloatNumber', 'data-mask' => '##0,00', 'max' => '200', 'step' => '.01', 'tabindex' => '3']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bp', __('messages.ipd_patient.bp').':', ['class' => 'form-label']) }}
                {{ Form::number('bp', null, ['class' => 'form-control', 'tabindex' => '4','placeholder'=>__('messages.ipd_patient.bp')]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('admission_date', __('messages.ipd_patient.admission_date') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::text('admission_date', null, ['placeholder' => __('messages.ipd_patient.admission_date'),'class' => getLoggedInUser()->thememode ? 'bg-light ipdAdmissionDate form-control' : 'bg-white ipdAdmissionDate form-control', 'id' => 'ipdAdmissionDate', 'autocomplete' => 'off', 'required', 'tabindex' => '5']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('doctor_id', __('messages.ipd_patient.doctor_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('doctor_id', $data['doctors'], null, ['class' => 'form-select', 'required', 'id' => 'ipdDoctorId', 'placeholder' => __('messages.web_home.select_doctor'), 'data-control' => 'select2', 'tabindex' => '6']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_type_id', __('messages.ipd_patient.bed_type_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_type_id', $data['bedTypes'], null, ['class' => 'form-select ipdBedTypeId', 'required', 'id' => 'ipdBedTypeId', 'placeholder' => __('messages.bed.select_bed_type'), 'data-control' => 'select2', 'tabindex' => '7']) }}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('bed_id', __('messages.ipd_patient.bed_id') . ':', ['class' => 'form-label']) }}
                <span class="required"></span>
                {{ Form::select('bed_id', [null], null, ['class' => 'form-select bedId', 'required', 'id' => 'ipdBedId', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . __('messages.bed_assign.bed')]) }}
            </div>
        </div>
    </div>
    @if ($customField >= 0)
        @foreach ($customField as $field)
            @php
                $field_values = explode(',',$field['values']);
                $dateType = ($field['field_type'] == 6) ? 'customFieldDate' : 'customFieldDateTime';
                $field_type = \App\Models\AddCustomFields::FIELD_TYPE_ARR[$field['field_type']];
                $fieldName = 'field'.$field['id'];
            @endphp
            <div class="form-group col-sm-{{$field['grid']}} mb-5 text-capitalize">
                {{ Form::label($field['field_name'], $field['field_name'] . ':', ['class' => $field['is_required'] == 1 ? 'form-label dynamic-field' : 'form-label']) }}
                @if ($field['is_required'] == 1)
                    <span class="required"></span>
                @endif
                @if ($field_type == 'date' || $field_type == 'date & Time' )
                    {{ Form::text($fieldName, null, ['id'=>$dateType,'class' => getLoggedInUser()->thememode || $field['is_required'] == 1 ? 'bg-light form-control dynamic-field' : 'bg-white form-control', 'autocomplete' => 'off','placeholder'=>$field['field_name'],$field['is_required'] == 1 ? 'required' : ''])}}
                 @elseif ($field_type == 'toggle')
                    <div class="form-check form-switch form-check-custom">
                        <input class="form-check-input w-35px h-20px is-active {{ $field['is_required'] == 1 ? 'dynamic-field' : ''}}" name={{$fieldName}} type="checkbox" value="1"
                            tabindex="8">
                    </div>
                @elseif ($field_type == 'select')
                    {{ Form::select($fieldName, $field_values, null, ['class' => $field['is_required'] == 1 ? 'form-select dynamic-field' : 'form-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2',$field['is_required'] == true ? 'required' : '']) }}
                @elseif ($field_type == 'multiSelect' )
                    {{ Form::select($fieldName.'[]', $field_values, 0, ['class' => $field['is_required'] == 1 ? 'dynamic-field form-select ipd-multi-select' : 'form-select ipd-multi-select', 'placeholder' => $field['field_name'], 'data-control' => 'select2','multiple' => true,$field['is_required'] == 1 ? 'required' : '']) }}
                @else
                    {{ Form::$field_type($fieldName, null, ['class' => $field['is_required'] == 1 ? 'form-control dynamic-field' : 'form-control', 'placeholder' => $field['field_name'],$field['is_required'] == 1 ? 'required' : '','rows' => $field_type == 'textarea' ?  4 : '']) }}
                @endif
            </div>
        @endforeach
    @endif
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="mb-5">
            <div class="mb-5 d-flex flex-column">
                {{ Form::label('is_old_patient', __('messages.ipd_patient.is_old_patient') . ':', ['class' => 'form-label']) }}
                <div class="form-check form-switch">
                    <input class="form-check-input w-35px h-20px {{getCurrentLoginUserLanguageName() == 'ar' ? 'float-end' : 'float-start'}}" name="is_old_patient" type="checkbox" value="1"
                        id="ipdFlexSwitchDefault">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('symptoms',__('messages.ipd_patient.symptoms').':', ['class' => 'form-label']) }}
                {{ Form::textarea('symptoms', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.symptoms')]) }}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-5">
            <div class="mb-5">
                {{ Form::label('notes',__('messages.ipd_patient.notes').':', ['class' => 'form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.ipd_patient.notes')]) }}
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => (getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary ms-2' : 'btn btn-primary me-2'), 'id' => 'ipdSave']) !!}
    <a href="{!! route('ipd.patient.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
</div>
