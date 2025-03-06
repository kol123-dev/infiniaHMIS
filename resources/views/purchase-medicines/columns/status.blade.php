@if ($row->payment_status == 1)
    <span class="badge bg-light-success">{{ __('messages.employee_payroll.paid') }}</span>
@else
    <span class="badge bg-light-danger">{{ __('messages.appointment.pending') }}</span>
@endif
