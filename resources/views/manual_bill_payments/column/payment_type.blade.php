@role('Admin')
    @if ($row->status == \App\Models\ManualBillPayment::Approved)
        <span class="badge bg-light-success">{{ __('messages.bill.approved') }}</span>
    @elseif($row->status == \App\Models\ManualBillPayment::Rejected)
        <span class="badge bg-light-danger">{{ __('messages.bill.rejected') }}</span>
    @else
        {{ Form::select('manualPayment', billStatus(), null, ['class' => 'form-select', 'data-id' => $row->id, 'id' => 'manualPayment', 'placeholder' => __('messages.medicine_bills.payment_status'), 'data-control' => 'select2', 'required']) }}
    @endif
@endrole
