<div class="d-flex  pe-25 {{getCurrentLoginUserLanguageName() == 'ar' ? 'justify-content-start' : 'justify-content-end'}}">
    @if($row->amount)
        {{ checkNumberFormat($row->amount, strtoupper(getCurrentCurrency())) }}
    @else
    {{ __('messages.common.n/a') }}
    @endif
</div>
