@if ($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_STRIPE)
    <span class="badge bg-light-primary">{{ __('messages.bill.stripe') }}</span>
@elseif($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_RAZORPAY)
    <span class="badge bg-light-success">{{ __('messages.razorpay') }}</span>
@elseif($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_PAYSTACK)
    <span class="badge bg-light-success">{{ __('messages.paystack') }}</span>
@elseif($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_CASH)
    <span class="badge bg-light-info">{{ __('messages.cash') }}</span>
@elseif($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_CHEQUE)
    <span class="badge bg-light-info">{{ __('messages.cheque') }}</span>
@elseif($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_PHONEPE)
    <span class="badge bg-light-info">{{ __('messages.phonepe') }}</span>
@elseif ($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_CHEQUE)
    <span class="badge bg-light-info">{{ __('messages.cheque') }}</span>
@elseif ($row->payment_type == \App\Models\MedicineBill::MEDICINE_BILL_FLUTTERWAVE)
    <span class="badge bg-light-info">{{ __('messages.flutterwave') }}</span>
@endif
