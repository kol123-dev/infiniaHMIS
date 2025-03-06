<div class="row gx-10 mb-5">
    <div class="form-group col-md-3 mb-5">
        {{ Form::label('patient_id', __('messages.prescription.patient') . ':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('patient_id', $patients, null, ['class' => 'form-select', 'required', 'id' => 'editPrescriptionPatientId', 'placeholder' => __('messages.document.select_patient')]) }}
    </div>
    @if (Auth::user()->hasRole('Doctor'))
        <input type="hidden" name="doctor_id" value="{{ Auth::user()->owner_id }}">
    @else
        <div class="form-group col-md-3 mb-5">
            {{ Form::label('doctor_name', __('messages.case.doctor') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            {{ Form::select('doctor_id', $doctors, null, ['class' => 'form-select', 'required', 'id' => 'editPrescriptionDoctorId', 'placeholder' => __('messages.web_home.select_doctor')]) }}
        </div>
    @endif
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('health_insurance', __('messages.prescription.health_insurance') . ':', ['class' => 'form-label']) }}
            {{ Form::text('health_insurance', null, ['class' => 'form-control', 'placeholder' => __('messages.prescription.health_insurance')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('low_income', __('messages.prescription.low_income') . ':', ['class' => 'form-label']) }}
            {{ Form::text('low_income', null, ['class' => 'form-control', 'placeholder' => __('messages.prescription.low_income')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('reference', __('messages.prescription.reference') . ':', ['class' => 'form-label']) }}
            {{ Form::text('reference', null, ['class' => 'form-control', 'placeholder' => __('messages.prescription.reference')]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label']) }}
            <br>
            <div class="form-check form-switch fv-row">
                <input name="status" class="form-check-input w-35px h-20px is-active" value="1" type="checkbox"
                    {{ isset($prescription) && $prescription->status ? 'checked' : '' }}>
                <label class="form-check-label" for="allowmarketing"></label>
            </div>
        </div>
    </div>
</div>
