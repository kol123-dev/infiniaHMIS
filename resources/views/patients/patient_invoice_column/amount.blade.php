<div class="d-flex justify-content-end pe-25">
    {{ checkNumberFormat($row->amount - ($row->amount * $row->discount / 100), strtoupper(getCurrentCurrency())) }}
</div>
