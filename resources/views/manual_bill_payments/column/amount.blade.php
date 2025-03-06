@if (!empty($row->amount))
    {{ checkNumberFormat($row->amount, strtoupper(getCurrentCurrency())) }}
@else
    {{ __('messages.common.n/a') }}
@endif
