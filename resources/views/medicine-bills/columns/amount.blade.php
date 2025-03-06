<div class="d-flex align-items-center justify-content-end mt-2">
    <p class="cur-margin me-5">
        {{ !empty($row->net_amount) ? getCurrencyFormat($row->net_amount) : __('messages.common.n/a') }}
    </p>
</div>
