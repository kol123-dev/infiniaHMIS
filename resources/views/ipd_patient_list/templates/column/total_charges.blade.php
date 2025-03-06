<div class="d-flex  pe-25">
    @if(!empty($row->bill->total_payments))
        {{ checkNumberFormat($row->bill->total_payments, strtoupper(getCurrentCurrency())) }}
    @else
        {{ checkNumberFormat(0, strtoupper(getCurrentCurrency())) }}
    @endif
</div>
