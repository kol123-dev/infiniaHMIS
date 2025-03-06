@if ($row->to_title)
    {{ $row->to_title }}
@else
    {{ __('messages.common.n/a') }}
@endif
