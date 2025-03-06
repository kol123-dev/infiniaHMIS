<div class="d-flex align-items-center mt-2">
    @if ($row->department->name === 'Admin')
        {{ __('messages.admin') }}
    @elseif($row->department->name === 'Doctor')
        {{ __('messages.doctors') }}
    @elseif($row->department->name === 'Patient')
        {{ __('messages.patients') }}
    @elseif($row->department->name === 'Receptionist')
        {{ __('messages.receptionists') }}
    @elseif($row->department->name === 'Nurse')
        {{ __('messages.nurses') }}
    @elseif($row->department->name === 'Pharmacist')
        {{ __('messages.pharmacists') }}
    @elseif($row->department->name === 'Lab Technician')
        {{ __('messages.lab_technicians') }}
    @elseif($row->department->name === 'Case Manager')
        {{ __('messages.case_manager') }}
    @elseif($row->department->name === 'Accountant')
        {{ __('messages.accountant.accountant') }}
    @endif
</div>
