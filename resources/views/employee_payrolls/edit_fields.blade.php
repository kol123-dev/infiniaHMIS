<div class="row gx-10 mb-5">
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('sr_no', __('messages.employee_payroll.sr_no').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('sr_no', null, ['class' => 'form-control', 'required','readonly']) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        @php $currentLang = app()->getLocale() @endphp
        {{ Form::label('payroll_id',__('messages.employee_payroll.payroll_id').':', ['class' => $currentLang == 'ru' ? 'label-display form-label' : 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('payroll_id', null, ['class' => 'form-control', 'required','readonly','placeholder' => __('messages.employee_payroll.payroll_id')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5 myclass">
        {{ Form::label('type',__('messages.employee_payroll.role').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('type', $types, $employeePayroll->type, ['id' => 'type','class' => 'form-select', 'required','placeholder' => __('messages.role.select_role'),'data-control' => 'select2']) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('owner_id',__('messages.employee_payroll.employee').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('owner_id', [], $employeePayroll->owner->id, ['placeholder' => __('messages.employee_payroll.employee'),'id' => 'ownerType','class' => 'form-select','required','disabled','data-control' => 'select2']) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('month',__('messages.employee_payroll.month').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::selectMonth('month',$employeePayroll->month, ['placeholder' => __('messages.employee_payroll.month'), 'id' => 'month','class' => 'form-select','required','data-control' => 'select2']) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('year', __('messages.employee_payroll.year').(':'), ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('year', null, ['class' => 'form-control','onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'maxlength' => '4','required','placeholder' => __('messages.employee_payroll.year')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('status', __('messages.common.status').(':'), ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::select('status', $status, null, ['id' => 'status','class' => 'form-select','required', 'data-control' => 'select2','placeholder' => __('messages.common.status')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('basic_salary', __('messages.employee_payroll.basic_salary').(':'), ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
        {{ Form::text('basic_salary', null, ['id' => 'basicSalary','class' => 'form-control price-input basicSalary','onkeyup' => 'if (parseInt(this.value.replace(/[^\d.]/g, "")) > 1000000) this.value = this.value.slice(0, -1)','required','placeholder' => __('messages.employee_payroll.basic_salary')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('allowance', __('messages.employee_payroll.allowance').(':'), ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('allowance', null, ['id' => 'allowance','class' => 'form-control price-input','onkeyup' => 'if (parseInt(this.value.replace(/[^\d.]/g, "")) >= 10000) this.value = this.value.slice(0, -1)','required','placeholder' => __('messages.employee_payroll.allowance')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('deductions', __('messages.employee_payroll.deductions').(':'), ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('deductions', null, ['placeholder' => __('messages.employee_payroll.deductions'),'id' => 'deductions','class' => 'form-control price-input','onkeyup' => 'if (parseInt(this.value.replace(/[^\d.]/g, "")) >= 10000) this.value = this.value.slice(0, -1)','required']) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('net_salary', __('messages.employee_payroll.net_salary').(':'),['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('net_salary', null, ['id' => 'netSalary','class' => 'form-control price-input','onkeyup' => 'if (/\D\./g.test(this.value)) this.value = this.value.replace(/\D\./g,"")', 'maxlength' => '7','required','readonly','placeholder' => __('messages.employee_payroll.net_salary')]) }}
    </div>
</div>
<div class="d-flex justify-content-end">
    {!! Form::submit(__('messages.common.save'), ['class' => (getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary btnSave ms-3' : 'btn btn-primary btnSave me-3'),'id' => 'editEmpPayrollSave']) !!}
    <a href="{{ route('employee-payrolls.index') }}"
       class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
</div>
