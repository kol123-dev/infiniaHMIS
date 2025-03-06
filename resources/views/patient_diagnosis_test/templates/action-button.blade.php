<div class="d-flex align-items-center">
    <a title="{{ __('messages.common.save') }}" class="btn px-2 fs-3 ps-0 text-warning"
        href="{{ route('employee.patient.diagnosis.test.pdf', $row->id) }}" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    <a href="{{ url('patient-diagnosis-test') . '/' . $row->id . '/edit' }}" title="{{ __('messages.common.edit') }}"
        class="btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a title="{{ __('messages.common.delete') }}" href="javascript:void(0)" data-id="{{ $row->id }}"
        wire:key="{{ $row->id }}" class="patient-diagnosys-test-delete-btn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2 ps-5' : ''}}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
