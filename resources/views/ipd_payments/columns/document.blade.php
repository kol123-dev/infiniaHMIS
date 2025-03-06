<div class="d-flex justify-content-end w-75 ps-125 text-center">
    @if ($row->ipd_payment_document_url != '')
        <a  href="{{ url('ipd-payment-download').'/'.$row->id }}" class="text-decoration-none">{{ __('messages.document.download') }}</a>
    @else
        {{__('messages.common.n/a') }}
    @endif
</div>
