<div class="">
    @if(!empty($row->basic_salary))
            {{ checkNumberFormat($row->basic_salary, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
