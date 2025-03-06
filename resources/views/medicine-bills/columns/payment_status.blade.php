<div class="d-flex align-items-center">
    @if($row->payment_status == App\Models\MedicineBill::UNPAID)
    <span class="badge bg-light-danger">{{__('messages.medicine_bills.unpaid')}}</span>
    @elseif($row->payment_status == App\Models\MedicineBill::PARTIALY_PAID)
     <span class="badge bg-light-warning">{{__('messages.medicine_bills.partially_paid')}}</span>
     @else
    <span class="badge bg-light-success">{{__('messages.medicine_bills.full_paid')}}</span>
    @endif
</div>
