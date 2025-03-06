<div class="listing-skeleton">
    <div class="card">
        <div class="card-content">
            <div class="d-flex justify-content-between">
                <div class="search-box pulsate rounded-1"> </div>
                <div class="d-flex">

                    @if(!Request::is('patient/my-cases'))
                        <div class="filter-box pulsate rounded-1 me-2"> </div>
                    @endif

                    {{-- show export box conditions --}}
                    @if (Request::is(
                            'nurses',
                            'receptionists',
                            'lab-technicians',
                            'pharmacists',
                            'appointments',
                            'doctors',
                            'incomes',
                            'expenses',
                            'call-logs',
                            'visitors',
                            'patients',
                            'case-handlers',
                            'patient-admissions',
                            'ambulances'))
                        <div class="export-box pulsate rounded-1 me-2"> </div>
                    @endif

                    {{-- show datebox size filter conditions --}}
                    @if (Request::is(
                            'appointments',
                            'doctor-opd-charges',
                            'item-categories',
                            'live-consultation',
                            'brands',
                            'advanced-payments'))
                        <div class="date-box pulsate rounded-1 me-2"> </div>
                    @endif

                    @if (!Request::is('employee/invoices','patient/my-cases','employee/patient-admissions'))
                        <div class="add-button-box pulsate rounded-1"> </div>
                    @endif
                </div>
            </div>
        </div>
        @include('livewire.skeleton_files.records')
    </div>
</div>
