<div class="d-flex align-items-center">
    <div class="image image-mini me-3">
        <a href="{{url('doctors',$row->appointment->doctor->id)}}">
            <div>
                <img src="{{$row->appointment->doctor->doctorUser->image_url}}" alt=""
                     class="user-img image image-circle object-contain">
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <a href="{{url('doctors',$row->appointment->doctor->id)}}"
           class="mb-1 text-decoration-none">{{$row->appointment->doctor->doctorUser->fullname}}</a>
        <span>{{$row->appointment->doctor->doctorUser->email}}</span>
    </div>
</div>
