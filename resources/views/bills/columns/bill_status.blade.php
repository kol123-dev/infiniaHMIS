<div class="d-flex align-items-center mt-2">
    @if ($row->status == '1')
        <span class="badge bg-light-success">{{ __('messages.employee_payroll.paid') }}</span>
    @elseif($row->status == '2')
        <span class="badge bg-light-danger">{{ __('messages.bill.rejected') }}</span>
    @else
        <span class="badge bg-light-danger">{{ __('messages.employee_payroll.not_paid') }}</span>
    @endif
</div>
