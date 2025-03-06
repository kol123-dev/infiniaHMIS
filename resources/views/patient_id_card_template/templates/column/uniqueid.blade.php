<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="patientUniqueId" data-id="{{$row->id}}" name="patient_unique_id" {{ $row->patient_unique_id == 1 ? 'checked' : '' }} value="1" role="switch" id="flexSwitchCheckDefault">
  </div>
