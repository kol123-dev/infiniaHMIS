<div class="d-flex align-items-center mt-2">
        @if ($row->discharge == 1)
            <span class="badge bg-light-success">{{ __('messages.ipd_patient.discharged') }}</span>
        @else
            <span class="badge bg-light-danger">{{ __('messages.ipd_patient.not_dischared') }}</span>
        @endif
</div>
