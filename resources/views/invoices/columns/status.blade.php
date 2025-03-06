<div class="d-flex align-items-center">
    @if ($row->status_label === 'Paid')
        <span class="badge bg-light-success fs-7">{{__('messages.invoice.paid')}}</span>
    @else
        <span class="badge bg-light-warning fs-7">{{__('messages.invoice.pending')}}</span>
    @endif
</div>
