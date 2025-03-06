<div class="d-flex align-items-center">
    @if ($row->module_name == 0)
        {{ __('messages.custom_field.appointment') }}
    @elseif ($row->module_name == 1)
        {{ __('messages.custom_field.ipd_patient') }}
    @elseif ($row->module_name == 2)
        {{ __('messages.custom_field.opd_patient') }}
    @elseif ($row->module_name == 3)
        {{ __('messages.custom_field.patient') }}
    @endif
</div>



