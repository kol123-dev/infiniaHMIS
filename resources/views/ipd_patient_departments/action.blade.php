<div class="d-flex align-items-center">
    @role('Admin|Doctor|Receptionist')
        @if (!$row->bill_status)
            <a href="{{ route('ipd.patient.edit', $row->id) }}" title="<?php echo __('messages.common.edit'); ?>"
                class="btn px-1 text-primary fs-3 ps-0" data-id="{{ $row->id }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endif
    @endrole
    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>" data-id="{{ $row->id }}" wire:key="{{ $row->id }}"
        class="deleteIpdDepartmentBtn btn px-1 text-danger fs-3 ps-0">
        <i class="fa-solid fa-trash me-1 {{getCurrentLoginUserLanguageName()=='ar' ? 'ps-5' : ''}}"></i>
    </a>
</div>
