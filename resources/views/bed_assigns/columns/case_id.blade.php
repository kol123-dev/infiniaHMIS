@if(!empty($row->ipdPatient))
    <div class="d-flex align-items-center mt-2">
        <a href="{{ url('ipds').'/'.$row->ipd_patient_department_id }}"
           class="badge bg-light-info text-decoration-none">{{ $row->ipdPatient->ipd_number }}</a>
    </div>
@else
    {{ __('messages.common.n/a') }}    
@endif

