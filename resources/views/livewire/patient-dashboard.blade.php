<div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                    <a class="text-decoration-none" href="{{ route('appointments.index') }}">
                        <div
                            class="bg-white shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                            <div
                                class="bg-primary widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-check fs-1 text-white"></i>
                            </div>
                            <div class="text-end">
                                <h2 class="fs-1-xxl fw-bolder text-primary">
                                    {{ $totlaAppointment }}</h2>
                                <h3 class="mb-0 fs-5 fw-bold text-dark">
                                    {{ __('messages.dashboard.total_appointment') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                    <a class="text-decoration-none" href="{{ route('appointments.index') }}">
                        <div
                            class="bg-white shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                            <div
                                class="bg-primary widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                <i class="fa-regular fa-calendar-plus fs-1 text-white"></i>
                            </div>
                            <div class="text-end">
                                <h2 class="fs-1-xxl fw-bolder text-primary">
                                    {{ $todayAppointment }}</h2>
                                <h3 class="mb-0 fs-5 fw-bold text-dark">
                                    {{ __('messages.dashboard.today_appointment') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                    <a class="text-decoration-none" href="{{ route('live.consultation.index') }}">
                        <div
                            class="bg-white shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                            <div
                                class="bg-primary widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                <i class="fa fa-video fs-1 text-white"></i>
                            </div>
                            <div class="text-end">
                                <h2 class="fs-1-xxl fw-bolder text-primary">
                                    {{ $totalMeeting }}</h2>
                                <h3 class="mb-0 fs-5 fw-bold text-dark">
                                    {{ __('messages.dashboard.total_meeting') }}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
                @if ($modules['Bills'] == true)
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a class="text-decoration-none" href="{{ route('employee.bills.index') }}">
                            <div
                                class="bg-white shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                    class="bg-primary widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-coins fs-1 text-white"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="fs-1-xxl fw-bolder text-primary">
                                        {{getCurrencySymbol().' '. formatCurrency($patientBill)}}</h2>
                                    <h3 class="mb-0 fs-5 fw-bold text-dark">
                                        {{ __('messages.dashboard.total_bills') }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                {{-- Appointment table --}}
                <div class="col-xxl-12 col-12 mb-7 mb-xxl-0">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-0">
                                {{ __('messages.dashboard.recent_appointments') }}
                            </h3>
                            <livewire:dashboard-appointment-table>
                        </div>
                    </div>
                </div>
                {{-- end Appointment table --}}
            </div>
        </div>
    </div>
</div>
