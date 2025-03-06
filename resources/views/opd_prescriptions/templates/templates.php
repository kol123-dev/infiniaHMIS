<script id="opdPrescriptionItemTemplate" type="text/x-jsrender">
    <tr>
        <td class="text-center opd-prescription-item-number">1</td>
        <td>
            <select class="form-select opdCategoryId select2Selector" name="category_id[]" placeholder="<?php echo  __('messages.medicine.select_category')?>" data-id="{{:uniqueId}}" required>
                <option selected="selected" value ><?php echo __('messages.medicine.select_category') ?></option>
                {{for medicineCategories}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <div class="medicinqtyclass"  id="medicineDiv{{:uniqueId}}" width:180px !important; ">
                <select class="form-select medicineId select2Selector" name="medicine_id[]" data-medicine-id="{{:uniqueId}}" disabled></select>
                <small class="availableQty"></small>
            </div>
        </td>
        <td>
            <input class="form-control dosage" name="dosage[]" type="text" onkeypress = 'return avoidSpace(event);' required placeholder="<?php echo __('messages.ipd_patient_prescription.dosage'); ?>">
        </td>
        <td>
            <select class="form-select opdDoseDuration select2Selector" name="day[]" data-id="{{:uniqueId}}">
                {{for opdDoseDuration}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select opdDoseInterval select2Selector" name="dose_interval[]" data-id="{{:uniqueId}}">
                {{for opdDoseInterval}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select opdPrescriptionMedicineMealId" name="time[]" data-id="{{:uniqueId}}">
                {{for meals}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <textarea class="form-control instruction" name="instruction[]" rows="1" onkeypress = 'return avoidSpace(event);' required placeholder="<?php echo __('messages.ipd_patient_prescription.instruction');?>"></textarea>
        </td>
        <td class="text-center">
            <i class="fa fa-trash text-danger deleteOpdPrescription cursor-pointer" data-edit="0"></i>
        </td>
    </tr>
</script>

<script id="editOpdPrescriptionItemTemplate" type="text/x-jsrender">
    <tr>
        <td class="text-center edit-opd-prescription-item-number" data-item-number="{{:uniqueId}}">1</td>
        <td>
            <select class="form-select opdCategoryId select2Selector" name="category_id[]" placeholder="<?php echo  __('messages.medicine.select_category') ?>" data-id="{{:uniqueId}}" required>
                <option selected="selected" value><?php echo  __('messages.medicine.select_category') ?></option>
                {{for medicineCategories}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <div class="medicinqtyclass" style="width:180px !important; ">
                <select class="form-select medicineId select2Selector" name="medicine_id[]" data-medicine-id="{{:uniqueId}}" disabled></select>
                <small class="availableQty"></small>
            </div>
        </td>
        <td>
            <input class="form-control" name="dosage[]" type="text" data-dosage-id="{{:uniqueId}}" onkeypress = 'return avoidSpace(event);' required placeholder="<?php echo __('messages.ipd_patient_prescription.dosage'); ?>">
        </td>
        <td>
            <select class="form-select opdDoseDuration select2Selector" name="day[]" data-dose-duration-id="{{:uniqueId}}">
                {{for opdDoseDuration}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select opdDoseInterval select2Selector" name="dose_interval[]" data-dose-interval-id="{{:uniqueId}}">
                {{for opdDoseInterval}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <select class="form-select opdPrescriptionMedicineMealId" name="time[]" data-meal-id="{{:uniqueId}}">
                {{for meals}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <textarea class="form-control" name="instruction[]" rows="1" data-instruction-id="{{:uniqueId}}" onkeypress = 'return avoidSpace(event);' required placeholder="<?php echo __('messages.ipd_patient_prescription.instruction');?>"></textarea>
        </td>
        <td class="text-center">
            <i class="fa fa-trash text-danger deleteOpdPrescriptionOnEdit cursor-pointer" data-edit="1"></i>
        </td>
    </tr>

</script>
