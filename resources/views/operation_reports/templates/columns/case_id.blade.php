<div class="d-flex align-items-center mt-2">
    @if (getLoggedinPatient())
        <a href="{{ url('patient' . '/' . 'my-cases' . '/' . $row->caseFromOperationReport->id) }}"
            class="badge bg-light-info text-decoration-none">{{ $row->case_id }}</a>
    @else
        <a href="{{ route('patient_case_show', $row->caseFromOperationReport->id) }}"
            class="badge bg-light-info text-decoration-none">{{ $row->case_id }}</a>
    @endif
</div>
