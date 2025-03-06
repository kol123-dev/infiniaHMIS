@if ($row->id_card !== null)
    {{ $row->id_card }}
@else
    {{ __('messages.common.n/a') }}
@endif
