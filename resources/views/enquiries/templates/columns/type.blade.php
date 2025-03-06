<div class="d-flex align-items-center mt-2">
  @if($row->enquiry_type == "Feedback/Suggestions")
    <span class="badge bg-light-primary fs-7">{{__('messages.enquiry.types.feedback/suggestions')}}</span>
@elseif($row->enquiry_type == "Residential Care")
    <span class="badge bg-light-success fs-7">{{__('messages.enquiry.types.residential_care')}}</span>
@else
    <span class="badge bg-light-warning fs-7">{{__('messages.enquiry.types.general_enquiry')}}</span>
@endif
</div>
