<div class="d-flex align-items-center">
    <div class="image image-circle image-mini me-3">
        <a href="{{route('patients.show',$row->patient->patient_id)}}">
            <img src="{{$row->patient->patient->patientUser->image_url}}" alt="user" class="user-img rounded-circle image object-contain">
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{route('patients.show',$row->patient->patient_id)}}" class="mb-1 text-decoration-none fs-6">
            {{$row->patient->patient->patientUser->full_name}}
        </a>
        <span class="fs-6">{{$row->patient->patient->patientUser->email}}</span>
    </div>
</div>
