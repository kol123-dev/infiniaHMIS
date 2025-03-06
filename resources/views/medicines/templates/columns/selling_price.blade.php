<div class="d-flex pe-25">
    @if($row->selling_price)
        {{ checkNumberFormat($row->selling_price, strtoupper(getCurrentCurrency())) }}
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
