<?php $patientRole = getLoggedInUser()->hasRole('Patient') ? true : false;
$doctorRole = getLoggedInUser()->hasRole('Doctor') ? true : false;
$adminRole = getLoggedInUser()->hasRole('Admin') ? true : false;
$today = Carbon\Carbon::now()->format('Y-m-d h:i A');
$meetingTime = \Carbon\Carbon::parse($row->consultation_date)->format('Y-m-d h:i A');

$googleUserEventSchedule = \App\Models\UserGoogleEventSchedule::where('user_id',$row->user->id)->where('google_live_consultation_id',$row->id)->first();
if (($row->platform_type == \App\Models\LiveConsultation::GOOGLE_MEET) && ! empty($googleUserEventSchedule->google_meet_link)) {
    $google_meet_link = $googleUserEventSchedule->google_meet_link;
} else {
    $google_meet_link = '';
}
?>

@if($row->platform_type == \App\Models\LiveConsultation::ZOOM)
    <a title="{{ ($patientRole) ? 'Join Meeting' : 'Start Meeting' }}"
        class="btn px-1 text-info fs-3 startConsultationBtn {{ ($row->status == 0 && $meetingTime > $today) ? '' : 'disabled' }}"
        data-id="{{ $row->id }}">
        <i class="fa-solid fa-video"></i>
    </a>
@else
    <a title="{{ ($patientRole) ? 'Join Meeting' : 'Start Meeting' }}" href={{ $google_meet_link }}
        target="_blank" class="btn px-1 text-info fs-3 {{ ($row->status == 0 && !empty($google_meet_link)) ? '' : 'disabled' }}" data-id="{{ $row->id }}">
        <i class="fa-solid fa-video"></i>
    </a>
@endif

@if ($doctorRole || $adminRole)
    @if ($row->status == 0)
        <a href="javascript:void(0)" title="<?php echo __('messages.common.edit'); ?>"
            class="btn px-1 text-primary fs-3 pe-0 editConsultationBtn" data-id="{{ $row->id }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    @endif
    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>" data-id="{{ $row->id }}"
        class=" deleteConsultationBtn btn px-1 text-danger fs-3 pe-0 " wire:key="{{ $row->id }}">
        <i class="fa-solid fa-trash"></i>
    </a>
@endif
