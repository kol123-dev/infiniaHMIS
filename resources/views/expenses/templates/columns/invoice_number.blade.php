@if ($row->invoice_number)
    <span class="badge bg-light-info text-decoration-none">{{ $row->invoice_number }}</span>
@else
    {{ __('messages.common.n/a') }}
@endif
