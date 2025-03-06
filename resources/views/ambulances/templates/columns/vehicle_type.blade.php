@if ($row->vehicle_type == 1)
    {{ __('messages.ambulance.contractual') }}
@else
    {{ __('messages.ambulance.owned') }}
@endif
