<div class="d-flex align-items-center mt-2">
    @if ($row->document_url !== '')
        <a  href="{{ url('income-download') . '/' . $row->id }}"
            class="text-decoration-none">{{ __('messages.incomes.download') }}</a>
    @else
        <samp>{{ __('messages.common.n/a') }}</samp>
    @endif
</div>
