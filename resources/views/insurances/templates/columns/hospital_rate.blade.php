<div class="text-end pe-25">
    @if(!empty($row->hospital_rate))
        {{ checkNumberFormat($row->hospital_rate, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
