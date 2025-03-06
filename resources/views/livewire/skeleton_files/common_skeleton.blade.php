<div class="listing-skeleton">
    <div class="card">
        <div class="card-content">
            <div class="d-flex justify-content-between">
                <div class="search-box pulsate rounded-1"> </div>
                <div class="d-flex">
                    {{-- show datebox size filter conditions --}}
                    @if (Request::is('holidays') && Request::get('section') != 'sidebar-setting')
                        <div class="date-box pulsate rounded-1 me-2"> </div>
                    @endif

                    {{-- show filterbox size filter conditions --}}
                    @if (Request::is('enquiries', 'live-consultation', 'investigation-reports', 'live-meeting', 'ipds') ||
                            Request::get('section') == 'sidebar-setting')
                        <div class="filter-box pulsate rounded-1 me-2"> </div>
                    @endif

                    {{-- hide datebox size filter conditions --}}
                    @if (
                        !Request::is(
                            'enquiries',
                            'live-consultation',
                            'patient-smart-card',
                            'employee/bills',
                            'investigation-reports',
                            'employee/notice-board',
                            'live-meeting') && Request::get('section') != 'sidebar-setting')
                        <div class="add-button-box pulsate rounded-1"> </div>
                    @endif
                </div>
            </div>
        </div>
        @include('livewire.skeleton_files.records')
    </div>
</div>
