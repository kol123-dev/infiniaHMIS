@if ($row->reference_no == null)
    {{ __('messages.common.n/a') }}
@else
    <span class="badge bg-light-info fs-7">{{ $row->reference_no }}</span>
@endif
