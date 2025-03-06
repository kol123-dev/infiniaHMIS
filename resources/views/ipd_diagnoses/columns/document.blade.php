@if ($row->ipd_diagnosis_document_url != '')
    <a  class="text-decoration-none"
        href="{{ url('ipd-diagnosis-download') . '/' . $row->id }}">{{ __('messages.document.download') }}</a>
@else
    {{ __('messages.common.n/a') }}
@endif
