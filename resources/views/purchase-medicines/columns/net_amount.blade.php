<div>
    {{ checkNumberFormat($row->net_amount,strtoupper(getCurrentCurrency())) }}
    {{-- {{number_format($row->net_amount,2)}} --}}
</div>
