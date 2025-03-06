<div class="d-flex align-items-center mt-2">
    {{ empty($row->user->designation) ? __('messages.common.n/a') : $row->user->designation }}
</div>
