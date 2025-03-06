<div>
    <div class="row">
        <div class="alert alert-danger d-none hide" id="breakErrorsBox"></div>
        <div class="col-sm-12 col-lg-6 mb-5">
            {{ Form::label('Doctor', __('messages.doctor_opd_charge.doctor') . ':', ['class' => 'form-label required']) }}
            {{ Form::select('doctor_id', $doctor, null, ['class' => 'io-select2 form-select', 'id' => 'adminAppointmentDoctorId', 'data-control' => 'select2', 'required', 'placeholder' => __('messages.doctor_opd_charge.doctor')]) }}
        </div>
        <div class="d-flex col-lg-2 form-check align-items-center break-form-check">
            <div class="me-10">
                <label class="form-label" for="editEveryDay">{{ __('messages.lunch_break.every_day') }}</label>
                {{ Form::radio('day', '1', true, ['class' => 'form-check-input', 'id' => 'editEveryDay', 'checked']) }}
            </div>
        </div>
        <div class="d-flex col-lg-2 form-check align-items-center break-form-check">
            <div class="me-10">
                <label class="form-label" for="editOneDay">{{ __('messages.lunch_break.single_day') }}</label>&nbsp;
                {{ Form::radio('day', null, false, ['class' => 'form-check-input', 'id' => 'editOneDay']) }}
            </div>
        </div>
        <div class="mb-5 col-6">
            {{ Form::label('break_from', __('messages.common.from') . ':', ['class' => 'form-label required']) }}
            {{ Form::text('break_from', '00:00:00', [
                'placeholder' => __('messages.common.from'),
                'id' => 'breakFrom',
                'class' => 'form-control breakFrom' . (getLoggedInUser()->thememode ? ' bg-light' : ' bg-white'),
                'required',
                'autocomplete' => 'off',
            ]) }}
        </div>
        <div class="mb-5 col-6">
            {{ Form::label('break_to', __('messages.email.to') . ':', ['class' => 'form-label required']) }}
            {{ Form::text('break_to', '00:00:00', [
                'id' => 'breakTo',
                'class' => 'form-control breakTo bm' . (getLoggedInUser()->thememode ? ' bg-light' : ' bg-white'),
                'required',
                'autocomplete' => 'off',
                'placeholder' => __('messages.email.to'),
            ]) }}
        </div>
        <div class="mb-5 col-6  d-none customiseDate">
            {{ Form::label('date', __('messages.sms.date') . ':', ['class' => 'form-label required']) }}
            {{ Form::text('date', null, ['class' => 'form-control' . (getLoggedInUser()->thememode ? ' bg-light' : ' bg-white'), 'placeholder' => __('messages.sms.date'), 'id' => 'doctorLunchBreakDate']) }}
        </div>
    </div>
    <div class="d-flex">
        <button type="submit" class="btn btn-primary"
            id="btnSubmit">{{ __('messages.common.save') }}</button>&nbsp;&nbsp;&nbsp;
        <a href="{{ route('breaks.index') }}" type="reset"
            class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
