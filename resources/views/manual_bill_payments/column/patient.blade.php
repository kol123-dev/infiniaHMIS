<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <a href="{{ route('patients.show',$row->bill->patient->id) }}">
            <div>
                <img src="{{ $row->bill->patient->patientUser->image_url }}" alt=""
                     class="user-img image image-circle object-contain" >
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('patients.show',$row->bill->patient->id) }}"
           class="text-decoration-none mb-1">{{ $row->bill->patient->patientUser->full_name }}</a>
        <span>{{ $row->bill->patient->patientUser->email }}</span>
    </div>
</div>
