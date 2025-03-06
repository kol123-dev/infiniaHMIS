<div class="text-end pe-25">
    @if($row->purchase_price)
        {{ checkNumberFormat($row->purchase_price, strtoupper(getCurrentCurrency())) }}
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
