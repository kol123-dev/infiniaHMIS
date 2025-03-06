<div class="d-flex justify-content-center align-items-center">
    <a href="{{ route('medicine-bills.show', [$row->id]) }}" class='btn px-2 text-primary fs-3 ps-0'> <i
        class="fas fa-eye text-success"></i></a>
{{--  @if (isset($row->payment_status) && $row->payment_status == false)  --}}

    @if ($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_CASH || $row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_CHEQUE)
        <a href="{{ route('medicine-bills.edit', [$row->id]) }}" title="<?php echo __('messages.common.edit'); ?>"
            class="btn px-2 edit-btn text-primary fs-3 py-2 {{getCurrentLoginUserLanguageName()=='ar' ? 'me-1' : ''}}">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    @endif

    <a title="<?php echo __('messages.common.delete'); ?>" data-id="{{ $row->id }}"
        class="btn px-2 medicine-bill-delete-btn px-2 text-danger fs-3 py-2">
        <i class="fa-solid fa-trash"></i>
    </a>

</div>
