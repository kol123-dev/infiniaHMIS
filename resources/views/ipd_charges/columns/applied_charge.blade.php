<div class="pe-4">
    @if(!empty($row->applied_charge))
        {{ checkNumberFormat($row->applied_charge, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
