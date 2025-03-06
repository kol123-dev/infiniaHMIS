<div class="text-start pe-10">
    @if(!empty($row->amount))
        {{ checkNumberFormat($row->amount, strtoupper(getCurrentCurrency())) }}
    @else
    @endif
</div>
