<div class="text-end pe-25">
    @if(!empty($row->service_tax))
        {{ checkNumberFormat($row->service_tax, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
