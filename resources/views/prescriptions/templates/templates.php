<script id="prescriptionMedicineTemplate" type="text/x-jsrender">
    <tr>
        <td class="table__item-desc">
            <div class="form-input" id="medicineDiv{{:uniqueId}}" style="width:180px;">
                <select class="form-select prescriptionMedicineId select2Selector" data-id="{{:uniqueId}}" name="medicine[]" placeholder="<?php echo __("messages.medicine_bills.select_medicine") ?>" required>
                    {{for medicines}}
                        <option value="{{:key}}">{{:value}}</option>
                    {{/for}}
                </select>
                <small class="AvailbleQty" id="AvailbleQty"></small>
            </div>
        </td>
        <td>
            <input class="form-control" name="dosage[]" type="test" placeholder="<?php echo __('messages.ipd_patient_prescription.dosage'); ?>">
        </td>
        <td>
            <select class="form-select prescriptionMedicineDurationId" name="day[]" data-id="{{:uniqueId}}">
                {{for doseDuration}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select prescriptionMedicineMealId" name="time[]" data-id="{{:uniqueId}}">
                {{for meals}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select prescriptionMedicineIntervalId" name="dose_interval[]" data-id="{{:uniqueId}}">
                {{for doseInterval}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <textarea class="form-control" name="comment[]" type="text" rows="1" placeholder="<?php echo __('messages.prescription.comment'); ?>"></textarea>
        </td>
        <td class="text-center">
            <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
               class="delete-prescription-medicine-item btn px-1 text-danger fs-3 pe-0">
                     <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>
</script>
