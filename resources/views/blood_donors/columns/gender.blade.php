@if ($row->gender == 0)
<span class="badge bg-light-primary">{{__('messages.blood_donor.female')}}</span>
@else
<span class="badge bg-light-success">{{__('messages.blood_donor.male')}}</span>
@endif
