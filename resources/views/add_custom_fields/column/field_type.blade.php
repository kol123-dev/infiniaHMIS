<div class="d-flex align-items-center">
    @if ($row->field_type == 0)
        {{ __('messages.custom_field.text') }}
    @elseif ($row->field_type == 1)
        {{ __('messages.custom_field.textarea') }}
    @elseif ($row->field_type == 2)
        {{ __('messages.custom_field.toggle') }}
    @elseif ($row->field_type == 3)
        {{ __('messages.custom_field.number') }}
    @elseif ($row->field_type == 4)
        {{ __('messages.custom_field.dropdown') }}
    @elseif ($row->field_type == 5)
        {{ __('messages.custom_field.multi_select') }}
    @elseif ($row->field_type == 6)
        {{ __('messages.custom_field.date') }}
    @elseif ($row->field_type == 7)
        {{ __('messages.custom_field.date_time') }}
    @endif
</div>



