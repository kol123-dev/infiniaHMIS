@if ($row->ref_no === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div>{{ $row->ref_no }}</div>
    </div>
@endif
