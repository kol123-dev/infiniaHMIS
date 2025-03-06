<div class="">
    @if(!empty($row->deductions))
            {{ checkNumberFormat($row->deductions, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
