@if ($row->document_url != '')
    <a  href="{{ url('visitors-download' . '/' . $row->id) }}"
        class="text-decoration-none">{{ __('messages.document.download') }}</a>
@else
    {{ __('messages.common.n/a')}}
@endif
