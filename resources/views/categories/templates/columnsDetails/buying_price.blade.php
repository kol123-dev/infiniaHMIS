<div class="d-flex justify-content-end pe-16">
    @if(!empty($row->buying_price))
        {{ checkNumberFormat($row->buying_price, strtoupper(getCurrentCurrency())) }}
    @else
    {{ __('messages.common.n/a') }}
    @endif
</div>
