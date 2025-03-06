<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive medicineTable">
            <table class="table table-striped" id="prescriptionMedicalTbl">
                <thead class="thead-dark">
                    <tr
                        class="{{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-end' : 'text-start' }} text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="">{{ __('messages.medicines') }}<span class="required"></span></th>
                        <th class="">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                        <th class="">{{ __('messages.purchase_medicine.dose_duration') }}<span
                                class="required"></span></th>
                        <th class="">{{ __('messages.prescription.time') }}<span class="required"></span></th>
                        <th class="">{{ __('messages.medicine_bills.dose_interval') }}<span
                                class="required"></span></th>
                        <th class="">{{ __('messages.prescription.comment') }}</th>
                        <th class="table__add-btn-heading text-center form-label fw-bolder text-gray-700 mb-3">
                            <a href="javascript:void(0)" type="button"
                                class="btn btn-primary text-star add-medicine-btn" id="addPrescriptionMedicineBtn">
                                {{ __('messages.common.add') }}
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="prescription-medicine-container">
                    @php
                        $dataId = 1;
                    @endphp
                    @if (isset($prescription))
                        @foreach ($prescription->getMedicine as $prescription)
                            <tr>
                                <td>
                                    <div class="form-inpu" id="medicineDiv{{ $dataId }}"
                                        style="width:180px;margin-top:22px;">
                                        {{ Form::select('medicine[]', $medicines['medicines'], $prescription->medicine, ['class' => 'form-select prescriptionMedicineId', 'data-id' => $dataId]) }}
                                        @foreach ($medicineQty as $key => $qty)
                                            <small
                                                class="{{ $key == $prescription->medicine && $qty == 0 ? 'text-danger' : 'text-success' }} AvailbleQtyClass"
                                                id="AvailbleQty">
                                                {{ $key == $prescription->medicine ? __('messages.item.available_quantity') . ' : ' . $qty : '' }}
                                            </small>
                                        @endforeach
                                    </div>
                                    @php
                                        $dataId++;
                                    @endphp
                                </td>
                                <td>
                                    {{ Form::text('dosage[]', $prescription->dosage, ['class' => 'form-control', 'id' => 'prescriptionMedicineNameId']) }}
                                </td>
                                <td>
                                    {{ Form::select('day[]', \App\Models\Prescription::DOSE_DURATION, $prescription->day, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                </td>
                                <td>
                                    {{ Form::select('time[]', \App\Models\Prescription::MEAL_ARR, $prescription->time, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                </td>
                                <td>
                                    {{ Form::select('dose_interval[]', \App\Models\Prescription::DOSE_INTERVAL, $prescription->dose_interval, ['class' => 'form-select prescriptionMedicineMealId']) }}
                                </td>
                                <td>
                                    {{ Form::textarea('comment[]', $prescription->comment, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('messages.prescription.comment')]) }}
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                        class="delete-prescription-medicine-item btn px-1 text-danger fs-3 pe-0">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                <div class="form-input" id="medicineDiv1" style="width:180px;">
                                    {{ Form::select('medicine[]', $medicines['medicines'], null, ['class' => 'form-select prescriptionMedicineId', 'data-id' => 1, 'placeholder' => __('messages.medicine_bills.select_medicine'), 'required']) }}
                                    <small class="" id="AvailbleQty"></small>
                                </div>
                            </td>
                            <td>
                                {{ Form::text('dosage[]', null, ['class' => 'form-control', 'id' => 'prescriptionMedicineNameId', 'placeholder' => __('messages.ipd_patient_prescription.dosage')]) }}
                            </td>
                            <td>
                                {{ Form::select('day[]', \App\Models\Prescription::DOSE_DURATION, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                            </td>
                            <td>
                                {{ Form::select('time[]', \App\Models\Prescription::MEAL_ARR, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                            </td>
                            <td>
                                {{ Form::select('dose_interval[]', \App\Models\Prescription::DOSE_INTERVAL, null, ['class' => 'form-select prescriptionMedicineMealId']) }}
                            </td>
                            <td>
                                {{ Form::textarea('comment[]', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('messages.prescription.comment')]) }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" title="{{ __('messages.common.delete') }}"
                                    class="delete-prescription-medicine-item btn px-1 text-danger fs-3 pe-0">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
