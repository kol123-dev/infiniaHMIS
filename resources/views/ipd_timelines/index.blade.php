<div class="card mb-5 mb-xl-10 p-9">
    <div class="card-title">
        @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Receptionist'))
            <a href="javascript:void(0)" class="btn btn-primary {{getCurrentLoginUserLanguageName()=='ar'?'float-start':'float-end'}}" data-bs-toggle="modal"
                data-bs-target="#addIpdTimelineModal">
                {{ __('messages.ipd_patient_timeline.new_ipd_timeline') }}
            </a>
        @endif
    </div>
    @if (Auth::user()->hasRole('Admin')|| Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Receptionist'))
        <div class="timeline-spacer"></div>
    @endif
    <div class="timeline-container mt-5">
        <div class="row">
            @if (!$ipdTimelines->isEmpty())
                <div class="col-lg-6 col-md-12 col-sm-12">
                    @foreach ($ipdTimelines as $ipdTimeline)
                        @if ($loop->odd)
                            <div class="timeline-item row"
                                date-is="{{ \Carbon\Carbon::parse($ipdTimeline->date)->translatedFormat('jS M, Y') }}">
                                <div class="col-md-4 align-items-center d-flex">
                                    <h3>{{ $ipdTimeline->title }}</h3>
                                </div>
                                <div class="col-md-5 align-items-center d-flex">
                                    <p>{!! !empty($ipdTimeline->description) ? nl2br(e($ipdTimeline->description)) : '' !!}</p>
                                </div>
                                <div class="{{getCurrentLoginUserLanguageName()=='ar'?'text-start':'text-end'}} bottom-sm mb-5 col-md-3">
                                    @if ($ipdTimeline->ipd_timeline_document_url != '')
                                        <a  title="download" class="btn px-1 text-info fs-2" target="_blank"
                                            href="{{ url('ipd-timeline-download' . '/' . $ipdTimeline->id) }}">
                                            <i class="fa fa-download action-icon"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Receptionist'))
                                        <a title="<?php echo __('messages.common.edit'); ?>" data-timeline-id="{{ $ipdTimeline->id }}"
                                            class="btn px-1 fs-2 text-primary edit-timeline-btn">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasRole('Admin'))
                                        <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>"
                                            data-timeline-id="{{ $ipdTimeline->id }}"
                                            class="delete-timeline-btn btn px-1 text-danger fs-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr class="text-white {{ (getLoggedInUser()->thememode ? 'bg-light' : 'bg-white') }}">
                        @endif
                    @endforeach
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    @foreach ($ipdTimelines as $ipdTimeline)
                        @if ($loop->even)
                            <div class="timeline-item row"
                                date-is="{{ \Carbon\Carbon::parse($ipdTimeline->date)->translatedFormat('jS M, Y') }}">
                                <div class="col-md-4 align-items-center d-flex">
                                    <h3>{{ $ipdTimeline->title }}</h3>
                                </div>
                                <div class="col-md-5 align-items-center d-flex">
                                    <p>{!! !empty($ipdTimeline->description) ? nl2br(e($ipdTimeline->description)) : '' !!}</p>
                                </div>
                                <div class="{{getCurrentLoginUserLanguageName()=='ar'?'text-start':'text-end'}} bottom-sm mb-5 col-md-3">
                                    @if ($ipdTimeline->ipd_timeline_document_url != '')
                                        <a  title="download" class="btn px-1 text-info fs-2" target="_blank"
                                            href="{{ url('ipd-timeline-download' . '/' . $ipdTimeline->id) }}">
                                            <i class="fa fa-download action-icon"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Receptionist'))
                                        <a title="<?php echo __('messages.common.edit'); ?>" data-timeline-id="{{ $ipdTimeline->id }}"
                                            class="btn px-1 fs-2 text-primary edit-timeline-btn">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasRole('Admin'))
                                        <a href="javascript:void(0)" title="<?php echo __('messages.common.delete'); ?>"
                                            data-timeline-id="{{ $ipdTimeline->id }}"
                                            class="delete-timeline-btn btn px-1 text-danger fs-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr class="text-white {{ (getLoggedInUser()->thememode ? 'bg-light' : 'bg-white') }}">
                        @endif
                    @endforeach
                </div>
            @else
                <div class="timeline-item">
                    <h3 class="my-0">{{ __('messages.ipd_patient_timeline.no_timeline_found') }}</h3>
                </div>
            @endif
        </div>

    </div>
</div>
