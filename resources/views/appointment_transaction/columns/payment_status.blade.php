@if ($row->appointment->payment_status == 0)
<div class="badge bg-light-warning">
    {{__('messages.appointment.pending')}}
</div>
@else
<div class="badge bg-light-success">
    {{ __('messages.common.confirm') }}
</div>
@endif
