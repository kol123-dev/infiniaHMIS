@if(Auth::user()->hasRole('Admin|Doctor'))
    <a href="javascript:void(0)" class="btn btn-primary float-end" data-bs-toggle="modal"
        data-bs-target="#addIpdPrescriptionModal">
        {{ __('messages.ipd_patient_prescription.new_prescription') }}
    </a>
@endif
