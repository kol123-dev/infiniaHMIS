@if ($row->attachment_url !== null)
    <a href="{{ url('investigation-download') . '/' . $row->id }}" target="_blank"
        class="text-decoration-none">{{ __('messages.document.download') }}</a>
@else
    <samp>{{ __('messages.common.n/a') }}</samp>
@endif
