@if(Auth::user()->hasRole('Admin|Doctor|Nurse|Receptionist'))
    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
       data-bs-target="#addOpdPrescriptionModal">
        {{ __('messages.ipd_patient_prescription.new_prescription') }}
    </a>
@endif
