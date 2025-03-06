<div class="">
    @if(!empty($row->net_salary))
            {{ checkNumberFormat($row->net_salary, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
