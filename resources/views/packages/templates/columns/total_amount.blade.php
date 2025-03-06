<div class="text-center pe-25">
    @if(!empty($row->total_amount))
        {{ checkNumberFormat($row->total_amount, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
