<div class="d-flex  pe-22 {{getCurrentLoginUserLanguageName()=='ar' ? 'justify-content-start' : 'justify-content-end'}}">
    @if(!empty($row->net_salary))
            {{ checkNumberFormat($row->net_salary, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
