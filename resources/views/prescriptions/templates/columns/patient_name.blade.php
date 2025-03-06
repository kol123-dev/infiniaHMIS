@if(getLoggedInUser()->hasRole('Nurse'))
    <div class="d-flex align-items-center">
        <div class="image image-mini me-3">
            <div>
                <img src="{{$row->patient->patientUser->image_url}}" alt=""
                     class="user-img image rounded-circle object-contain">
            </div>
        </div>
        <div class="d-flex flex-column">
            {{$row->patient->patientUser->full_name}}
            <span>{{$row->patient->patientUser->email}}</span>
        </div>
    </div>
@else
    <div class="d-flex align-items-center">
        <div class="image image-mini me-3">
            <a href="{{route('patients.show',$row->patient->id)}}">
                <div>
                    <img src="{{$row->patient->patientUser->image_url}}" alt=""
                         class="user-img image rounded-circle object-contain">
                </div>
            </a>
        </div>
        <div class="d-flex flex-column">
            <a href="{{route('patients.show',$row->patient->id)}}"
               class="mb-1 text-decoration-none">{{$row->patient->patientUser->full_name}}</a>
            <span>{{$row->patient->patientUser->email}}</span>
        </div>
    </div>
@endif
