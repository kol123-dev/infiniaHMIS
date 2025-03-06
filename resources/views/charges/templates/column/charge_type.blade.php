@if($row->charge_type == 1)
    <span class="badge bg-light-primary">{{__('messages.charge.Investigations')}}</span>
@elseif($row->charge_type == 2)
    <span class="badge bg-light-info">{{__('messages.charge.Operation Theatre')}}</span>
@elseif($row->charge_type == 3)
    <span class="badge bg-light-success">{{__('messages.charge.Others')}}</span>
@elseif($row->charge_type == 4)
    <span class="badge bg-light-danger">{{__('messages.charge.Procedures')}}</span>
@else
    <span class="badge bg-light-warning">{{__('messages.charge.Supplier')}}</span>
@endif
