@if (Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Admin'))
    <a href="{{ url('employee-payrolls', $row->id) }}"><span
            class="badge bg-light-info fs-6">{{ $row->payroll_id }}</span></a>
@else
    <span class="badge bg-light-info fs-6">{{ $row->payroll_id }}</span>
@endif
