<div class="d-flex  {{getCurrentLoginUserLanguageName() == 'ar' ? 'justify-content-start ps-25' : 'justify-content-end pe-25'}}">
    @if(!empty($row->amount))
        {{ checkNumberFormat($row->amount, strtoupper(getCurrentCurrency())) }}
    @endif
</div>
