<script id="pathologyParameterTemplate" type="text/x-jsrender">
    <tr>
    <td class="table__item-desc">
            <select class="form-select patholory-parameter-data select2Selector" name="parameter_id[]" data-id="{{:uniqueId}}" required>
            <option value="" disabled selected> __('messages.new_change.select_parameter_name')</option>
            {{for parameters}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td>
            <input class="form-control" name="patient_result[]" type="text" placeholder="<?php echo  __('messages.new_change.patient_result'); ?>">
        </td>
        <td>
            <input class="form-control" required="" value='' name="reference_range[]" id="rangeId" type="text" readonly placeholder="<?php echo  __('messages.new_change.reference_range'); ?>">
        </td>
        <td>
            <input class="form-control" required="" value='' name="unit_id[]" id="unitId" type="text" readonly placeholder="<?php echo  __('messages.pathology_test.unit'); ?>">
        </td>
        <td class="text-center">
            <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
               class="delete-parameter-test  btn px-1 text-danger fs-3 pe-0">
                     <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>

</script>
