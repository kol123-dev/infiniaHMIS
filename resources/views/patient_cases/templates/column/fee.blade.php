<div class="text-end pe-22">
    @if(!empty($row->fee))
        {{ checkNumberFormat($row->fee, strtoupper(getCurrentCurrency())) }}
    @else
    {{ __('messages.common.n/a') }}
    @endif
</div>
