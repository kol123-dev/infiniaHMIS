<div class="d-flex justify-content-center">
    @role('Admin|Patient|Receptionist')
        <button type="button" class="btn px-1 text-primary fs-3 ShowPatientCardData" data-id="{{ $row->patient_unique_id }}"
            data-bs-toggle="modal" data-bs-target="#ShowPatientCardDataModal">
            <i class="fas fa-eye"></i>
        </button>
    @endrole
    @role('Patient|Admin|Receptionist')
        <a href="{{ route('patient.id.card.pdf', $row->id) }}" target="_blank" class="btn px-1 text-primary fs-3">
            <i class="fa fa-download" aria-hidden="true"></i>
        </a>
    @endrole
    @role('Admin|Receptionist')
        <a href="javascript:void(0)" data-id="{{ $row->id }}"
            class="btn px-1 text-danger fs-3 generate-patient-card-delete-btn">
            <i class="fa-solid fa-trash"></i>
        </a>
    @endrole
</div>
