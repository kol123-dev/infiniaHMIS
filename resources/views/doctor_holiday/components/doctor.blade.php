@if(Auth::user()->hasRole('Doctor|Case Manager|Pharmacist|Lab Technician'))
    <div class="d-flex align-items-center">
        <div class="image image-circle image-mini me-3">
            <a href="{{url('employee/doctor') . '/' .$row->id}}">
                <div>
                    <img src="{{$row->doctor->doctorUser->image_url}}" alt=""
                         class="user-img rounded-circle object-contain" height="35" width="35">
                </div>
            </a>
        </div>
        <div class="d-flex flex-column">
            <a href="{{url('employee/doctor') . '/' . $row->id}}"
               class="text-decoration-none mb-1">{{$row->doctor->doctorUser->full_name}}</a>
            <span>{{$row->doctor->doctorUser->email}}</span>
        </div>
    </div>
@elseif(Auth::user()->hasRole('Patient'))
    <div class="d-flex align-items-center">
        <div class="image image-circle image-mini me-3">
                <div>
                    <img src="{{$row->doctor->doctorUser->image_url}}" alt=""
                         class="user-img rounded-circle object-contain" height="35" width="35">
                </div>
        </div>
        <div class="d-flex flex-column">
            {{$row->doctor->doctorUser->full_name}}
            <span>{{$row->doctor->doctorUser->email}}</span>
        </div>
    </div>
@else
    <div class="d-flex align-items-center">
        <div class="image image-circle image-mini me-3">
            <a href="{{route('doctors_show',$row->id)}}">
                <div>
                    <img src="{{$row->doctor->doctorUser->image_url}}" alt=""
                         class="user-img rounded-circle object-contain" height="35" width="35">
                </div>
            </a>
        </div>
        <div class="d-flex flex-column">
            <a href="{{route('doctors_show',$row->doctor->id)}}"
               class="text-decoration-none mb-1">{{$row->doctor->doctorUser->full_name}}</a>
            <span>{{$row->doctor->doctorUser->email}}</span>
        </div>
    </div>

@endif
