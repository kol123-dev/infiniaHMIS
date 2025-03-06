@if ($row->opd_diagnosis_document_url != '')
    <a  href="{{ url('opd-diagnosis-download' . '/' . $row->id) }}" class="text-decoration-none">
        {{ __('messages.document.download') }}
    </a>
@else
    {{ __('messages.common.n/a') }}
@endif
