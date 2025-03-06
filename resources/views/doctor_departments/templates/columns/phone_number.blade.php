@if ($row->doctorUser->phone)
    {{ $row->doctorUser->phone }}
@else
    {{ __('messages.common.n/a') }}
@endif
