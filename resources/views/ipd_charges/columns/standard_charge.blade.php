<div class="pe-4">
    @if(!empty($row->standard_charge))
        {{ checkNumberFormat($row->standard_charge, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
