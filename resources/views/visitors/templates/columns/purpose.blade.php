@if ($row->purpose == 1)
{{ __('messages.visitor.enquiry') }}
@elseif ($row->purpose == 2)
{{ __('messages.visitor.seminar') }}
@else
{{ __('messages.visitor.visit') }}
@endif
