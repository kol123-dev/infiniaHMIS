<div class="row">
    {{ Form::hidden('currency_symbol', getCurrentCurrency(), ['class' => 'currencySymbol']) }}
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('patient_admission_id', __('messages.bill.admission_id').(':'),['class'=>'form-label']) }}
        <span class="required"></span>
        {{ Form::select('patient_admission_id', $patientAdmissionIds, null, ['class' => 'form-select', 'id' => 'patientAdmissionId', 'placeholder' =>__('messages.common.choose').' '. __('messages.bill.admission_id'),'data-control' => 'select2', 'required']) }}
    </div>
    {{ Form::hidden('patient_admission_id', null, ['id' => 'pAdmissionId']) }}
    {{ Form::hidden('patient_id', null, ['id' => 'billsPatientId']) }}
    @if(isset($bill))
        <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
            {{ Form::label('bill_date', __('messages.bill.bill_date').(':'),['class'=>'form-label']) }}
            <span class="required"></span>
            {{ Form::text('bill_date', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'id' => 'editBillDate', 'autocomplete' => 'off', 'placeholder' => __('messages.bill.bill_date')]) }}
        </div>
    @else
        <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
            {{ Form::label('bill_date', __('messages.bill.bill_date').(':'),['class'=>'form-label']) }}
            <span class="required"></span>
            {{ Form::text('bill_date', null, ['class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'id' => 'bill_date', 'autocomplete' => 'off', 'placeholder' => __('messages.bill.bill_date')]) }}
        </div>
    @endif
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5 myclass">
        {{ Form::label('name', __('messages.case.patient').(':'),['class'=>'form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly', 'placeholder' => __('messages.case.patient')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('email', __('messages.bill.patient_email').(':'),['class'=>'form-label']) }}
        {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'userEmail', 'readonly', 'placeholder' => __('messages.bill.patient_email')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('phone', __('messages.bill.patient_cell_no').(':'),['class'=>'form-label']) }}
        {{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'userPhone', 'readonly', 'placeholder' => __('messages.bill.patient_cell_no')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('gender', __('messages.bill.patient_gender').(':'),['class'=>'form-label']) }}
        <br>
        <div class="d-flex align-items-center mt-3">
            <div class="form-check {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-10' : 'me-10'}} mb-0">
                {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6', 'id' => 'genderMale']) }} &nbsp;
                <label class="form-check-label"
                       for="genderMale">{{ __('messages.user.male') }}</label>
            </div>
            <div class="form-check mb-0">
                {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7', 'id' => 'genderFemale']) }}
                <label class="form-check-label"
                       for="genderFemale">{{ __('messages.user.female') }}</label>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('dob', __('messages.bill.patient_dob').(':'),['class'=>'form-label']) }}
        {{ Form::text('dob', null, ['class' => 'form-control', 'id' => 'dob', 'readonly', 'placeholder' => __('messages.bill.patient_dob')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('doctor_id', __('messages.case.doctor').(':'),['class'=>'form-label']) }}
        {{ Form::text('doctor_id', null, ['class' => 'form-control', 'id' => 'billDoctorId', 'readonly', 'placeholder' => __('messages.case.doctor')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('admission_date', __('messages.bill.admission_date').(':'),['class'=>'form-label']) }}
        {{ Form::text('admission_date', null, ['class' => 'form-control', 'id' => 'admissionDate', 'readonly', 'placeholder' =>__('messages.bill.admission_date')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('discharge_date', __('messages.bill.discharge_date').(':'),['class'=>'form-label']) }}
        {{ Form::text('discharge_date', null, ['class' => 'form-control', 'id' => 'dischargeDate', 'readonly', 'placeholder' => __('messages.bill.discharge_date')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('package_id', __('messages.bill.package_name').(':'),['class'=>'form-label']) }}
        {{ Form::text('package_id', null, ['class' => 'form-control', 'id' => 'packageId', 'readonly', 'placeholder' => __('messages.bill.package_name')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('insurance_id', __('messages.bill.insurance_name').(':'),['class'=>'form-label']) }}
        {{ Form::text('insurance_id', null, ['class' => 'form-control', 'id' => 'insuranceId', 'readonly', 'placeholder' => __('messages.bill.insurance_name')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('total_days', __('messages.bill.total_days').(':'),['class'=>'form-label']) }}
        {{ Form::text('total_days', null, ['class' => 'form-control', 'id' => 'totalDays', 'readonly', 'placeholder' => __('messages.bill.total_days')]) }}
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 mb-5">
        {{ Form::label('policy_no', __('messages.bill.policy_no').(':'),['class'=>'form-label']) }}
        {{ Form::text('policy_no', null, ['class' => 'form-control', 'id' => 'policyNo', 'readonly', 'placeholder' => __('messages.bill.policy_no')]) }}
    </div>
</div>

<div class="com-sm-12">
    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end mb-4">
        <button type="button" class="btn btn-primary text-star" id="addBillItem"> {{ __('messages.invoice.add') }}</button>
    </div>
    <div class="table-responsive-sm mb-4">
        <table class="table table-striped" id="billTbl">
            <thead>
            <tr class="{{getCurrentLoginUserLanguageName() == 'ar' ? 'text-end' : 'text-start'}} text-muted fw-bolder fs-7 text-uppercase gs-0">
                <th class="text-center">#</th>
                <th class="required">{{ __('messages.bill.item_name') }}</th>
                <th class="required">{{ __('messages.bill.qty') }}</th>
                <th class="required">{{ __('messages.bill.price') }}</th>
                <th class="text-right">{{ __('messages.bill.amount') }}</th>
                <th class="text-center">
                    {{ __('messages.common.action') }}
                </th>
            </tr>
            </thead>
            <tbody class="bill-item-container text-gray-600 fw-bold">
            @if(isset($bill))
                @foreach($bill->billItems as $billItem)
                    <tr>
                        <td class="text-center item-number">{{ $loop->iteration }}</td>
                        <td class="table__item-desc">
                            {{ Form::text('item_name[]', $billItem->item_name, ['class' => 'form-control itemName','required', 'placeholder' => __('messages.bill.item_name')]) }}
                        </td>
                        <td class="table__qty">
                            {{ Form::number('qty[]', $billItem->qty, ['class' => 'form-control qty quantity','required', 'placeholder' => __('messages.bill.qty')]) }}
                        </td>
                        <td>
                            {{ Form::text('price[]', number_format($billItem->price,2), ['class' => 'form-control price-input price','required', 'placeholder' => __('messages.bill.price')]) }}
                        </td>
                        <td class="amount text-right itemTotal">{{ number_format($billItem->amount,2) }}
                        </td>
                        <td class="text-center">
                            <i class="fa fa-trash text-danger delete-bill-add-item pointer"></i>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center item-number">1</td>
                    <td class="table__item-desc">
                        {{ Form::text('item_name[]', null, ['class' => 'form-control itemName','required', 'placeholder' => __('messages.bill.item_name')]) }}
                    </td>
                    <td class="table__qty">
                        {{ Form::number('qty[]', null, ['class' => 'form-control qty quantity','required','placeholder' => __('messages.bill.qty')]) }}
                    </td>
                    <td>
                        {{ Form::text('price[]', null, ['class' => 'form-control price-input test price','required','placeholder' => __('messages.bill.price')]) }}
                    </td>
                    <td class="amount text-right itemTotal">
                        0.00
                    </td>
                    <td class="text-center">
                        <i class="fa fa-trash text-danger delete-invoice-item pointer"></i>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-4  {{getCurrentLoginUserLanguageName()=='ar' ? 'float-end' : 'float-right'}} p-0">
        <table class="w-100">
            <tbody class="bill-item-footer">
            <tr>
                <td class="form-label text-right">{{ __('messages.bill.total_amount').(':') }}</td>
                <td class="text-right">
                    <span id="totalPrice" class="price">{{ isset($bill) ? getCurrencySymbol() . '' . number_format($bill->amount,2) : getCurrencySymbol() . '' . 0 }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Total Amount Field -->
{{ Form::hidden('total_amount', null, ['class' => 'form-control', 'id' => 'totalAmount']) }}

<!-- Submit Field -->
<div class="{{getCurrentLoginUserLanguageName()=='ar' ? 'float-start' : 'float-end'}}">
    {{ Form::submit(__('messages.common.save'), ['class' =>getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary ms-3' : 'btn btn-primary me-3','id' => 'billSave']) }}
    <a href="{{ route('bills.index') }}"
       class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
</div>
