<?php $patientRole = getLoggedInUser()->hasRole('Patient') ? true : false;
$doctorRole = getLoggedInUser()->hasRole('Doctor') ? true : false;
$adminRole = getLoggedInUser()->hasRole('Admin') ? true : false;
$today = Carbon\Carbon::now()->format('Y-m-d h:i A');
$meetingTime = \Carbon\Carbon::parse($row->consultation_date)->format('Y-m-d h:i A');
?>

@if ($row->status == 0 && $meetingTime > $today)
    <a title="{{ !($adminRole || $doctorRole) ? __('messages.live_consultation.join_meeting') : __('messages.live_consultation.start_meeting') }}"
        class="btn px-1 text-info fs-3 pe-1 startMeetingBtn" data-id="{{ $row->id }}">
        <i class="fa-solid fa-video"></i>
    </a>
@endif
@if ($doctorRole || $adminRole)
    @if ($row->status == 0 && $meetingTime > $today)
        <a href="javascript:void(0)" title="<?php echo __('messages.common.edit'); ?>" class="btn px-1 text-primary fs-3 pe-0 editMeetingBtn"
            data-id="{{ $row->id }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    @endif
    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>" data-id="{{ $row->id }}"
        class="deleteMeetingBtn btn px-1 text-danger fs-3 pe-0" wire:key="{{ $row->id }}">
        <i class="fa-solid fa-trash"></i>
    </a>
@endif
