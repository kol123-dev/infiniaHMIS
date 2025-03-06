@if ($row->from_title)
    {{ $row->from_title }}
@else
    {{ __('messages.common.n/a') }}
@endif
