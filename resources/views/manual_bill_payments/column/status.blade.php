@if ($row->status == '1')
    <span class="badge bg-light-success">{{ __('messages.employee_payroll.paid') }}</span>
@else
    <span class="badge bg-light-danger">{{ __('messages.employee_payroll.not_paid') }}</span>
@endif
