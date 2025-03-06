<div class="d-flex align-items-center">
    <a href="{{ route('bills.pdf', ['bill' => $row->id]) }}" title="<?php echo __('messages.bill.print_bill'); ?>"
        class="btn px-1 text-warning fs-3 ps-0 " target="_blank">
        <i class="fa fa-print" aria-hidden="true"></i>
    </a>

    @if ($row->status == 0)
        <a href="{{ route('bills.edit', $row->id) }}" title="<?php echo __('messages.common.edit'); ?>"
            class="btn px-1 text-primary fs-3 ps-0 bill-edit-btn">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    @endif

    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>" data-id="{{ $row->id }}"
        class="bill-delete-btn  btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName() == 'ar' ? 'me-2' : ''}}" wire:key="{{ $row->id }}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
