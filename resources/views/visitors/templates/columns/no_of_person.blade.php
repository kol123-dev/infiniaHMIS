@if ($row->no_of_person !== null)
    {{ $row->no_of_person }}
@else
    {{ __('messages.common.n/a') }}
@endif
