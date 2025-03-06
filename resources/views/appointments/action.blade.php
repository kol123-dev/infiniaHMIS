
<div class="d-flex align-items-center">
    @if (!$row->is_completed == 3 && in_array($row->payment_type, [1,6,2]))
        <a data-bs-toggle="tooltip" data-placement="top" data-bs-original-title="{{ __('messages.common.cancel') }}"
            data-id="{{ $row->id }}"
            class="cancel-appointment btn px-1 text-danger fs-3 pe-0 {{ $row->is_completed == 1 ? 'd-none' : '' }}">
            <i class="far fa-calendar-times {{ $row->is_completed == 1 ? 'text-danger' : '' }}"></i>
        </a>
    @endif
    @if (!getLoggedinPatient() && $row->is_completed == 0)
        <a title="{{ __('messages.common.confirm') }}" data-id="{{ $row->id }}"
            class="appointment-complete-status btn px-1 text-primary fs-3 pe-0">
            <i class="far fa-calendar-check"></i>
        </a>
    @endif
    @if (Auth::user()->hasRole('Admin'))
        @if (
            ($row->is_completed == 0 || $row->is_completed == 1) &&
                ($row->payment_type == 1 || $row->payment_type == 2 || $row->payment_type == 6 || $row->payment_type == null))
            <a href="{{ route('appointments.edit', ['appointment' => $row->id]) }}"
                title="{{ __('messages.common.edit') }}" class="btn px-1 text-primary fs-3 ps-1">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endif
    @endif
</div>
