@role('Patient')
    @if ($row->payment_mode == '0')
        <span class="badge bg-light-primary">{{ __('messages.bill.stripe') }}</span>
    @elseif($row->payment_mode == '1')
        <span class="badge bg-light-info">{{ __('messages.bill.manually') }}</span>
    @elseif($row->payment_mode == '2')
        <span class="badge bg-light-success">{{ __('messages.razorpay') }}</span>
    @elseif($row->payment_mode == '8')
        <span class="badge bg-light-success">{{ __('messages.flutterwave') }}</span>
    @elseif($row->payment_mode == '5')
        <span class="badge bg-light-primary">{{ __('messages.phonepe') }}</span>
    @elseif($row->payment_mode == '3')
        <span class="badge bg-light-success">{{ __('messages.paystack') }}</span>
    @else
        {{-- {{ Form::select('payment_type', getPaymentTypes(), null, ['id' => 'paymentModeType', 'data-id' => $row->id, 'class' => 'form-select', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.ipd_payments.payment_mode'), 'data-control' => 'select2', 'required']) }} --}}
        {{ Form::select('payment_type', getPaymentTypes(), null, ['class' => 'form-select paymentModeType', 'data-id' => $row->id, 'placeholder' => __('messages.common.choose') . ' ' . __('messages.ipd_payments.payment_mode'), 'data-control' => 'select2', 'required']) }}
    @endif
@endrole
