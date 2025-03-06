<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <a href="{{route('patients.show',$row->appointment->patient->id)}}">
            <div>
                <img src="{{$row->appointment->patient->patientUser->image_url}}" alt=""
                     class="user-img image image-circle object-contain">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{route('patients.show',$row->appointment->patient->id)}}"
           class="mb-1 text-decoration-none">{{$row->appointment->patient->patientUser->fullname}}</a>
        <span>{{$row->appointment->patient->patientUser->email}}</span>
    </div>
</div>
