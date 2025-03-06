@if ($row->document_url)
    <a  href="{{ url('document-download' . '/' . $row->id) }}" target="_blank"
        class="text-decoration-none">{{ __('messages.document.download') }}</a>
@else
    {{ __('messages.common.n/a') }}
@endif
