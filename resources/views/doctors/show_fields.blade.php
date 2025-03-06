<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-5 col-12">
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-body pt-9 pb-0">
                            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                                <div class=" mb-4 {{getCurrentLoginUserLanguageName() == 'ar' ? ' ms-7' : 'me-7'}}">
                                    <div class="image image-circle image-small">
                                        <img src="{{ $doctorData->user->image_url }}" class="object-fit-cover"
                                            alt="image" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="#"
                                                    class="text-gray-800 text-hover-primary fs-2  {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}} text-decoration-none">{{ $doctorData->user->full_name }}</a>
                                                <span
                                                    class="text-{{ $doctorData->user->status === 1 ? 'success' : 'danger' }} mb-2 d-block">{{ $doctorData->user->status === 1 ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                                            </div>
                                            <a href="mailto: {{ $doctorData->user->email }}"
                                                class="text-decoration-none d-flex align-items-center text-gray-600 text-hover-primary mb-2 {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-2' : 'me-2'}}">
                                                {{ $doctorData->user->email }}
                                            </a>
                                            <span
                                                class="d-flex align-items-center text-gray-600 text-hover-primary  {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-5' : 'me-5'}} mb-2">
                                                @if (
                                                    !empty($doctorData->address->address1) ||
                                                        !empty($doctorData->address->address2) ||
                                                        !empty($doctorData->address->city) ||
                                                        !empty($doctorData->address->zip))
                                                    <span><i class="fas fa-location"></i></span>
                                                @endif
                                                <span class="p-2">
                                                    {{ !empty($doctorData->address->address1) ? $doctorData->address->address1 : '' }}{{ !empty($doctorData->address->address2) ? (!empty($doctorData->address->address1) ? ',' : '') : '' }}
                                                    {{ empty($doctorData->address->address1) || !empty($doctorData->address->address2) ? (!empty($doctorData->address->address2) ? $doctorData->address->address2 : '') : '' }}
                                                    {{ !empty($doctorData->address->city) ? ',' . $doctorData->address->city : '' }}
                                                    {{ !empty($doctorData->address->zip) ? ',' . $doctorData->address->zip : '' }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-12">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($doctorData->cases) ? $doctorData->cases->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{ __('messages.patient.total_cases') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($doctorData->patients) ? $doctorData->patients->count() : 0 }}</h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">{{ __('messages.patients') }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-6 mb-md-0">
                            <div class="border rounded-10 p-5 h-100">
                                <h2 class="text-primary mb-3">
                                    {{ !empty($doctorData->appointments) ? $doctorData->appointments->count() : 0 }}
                                </h2>
                                <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                    {{ __('messages.patient.total_appointments') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-7 overflow-hidden">
        <ul class="nav nav-tabs mb-5 pb-1 overflow-auto flex-nowrap text-nowrap">
            <li
                class="nav-item position-relative  {{ getCurrentLoginUserLanguageName() == 'ar' ? ' ms-7' : 'me-7' }} mb-3">
                <a class="nav-link active p-0" data-bs-toggle="tab"
                    href="#doctorOverview">{{ __('messages.overview') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#doctorCases">{{ __('messages.cases') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#doctorPatients">{{ __('messages.patients') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#doctorAppointments">{{ __('messages.appointments') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab" href="#doctorSchedules">{{ __('messages.schedules') }}</a>
            </li>
            <li class="nav-item position-relative me-7 mb-3">
                <a class="nav-link p-0" data-bs-toggle="tab"
                    href="#doctorPayroll">{{ __('messages.my_payroll.my_payrolls') }}</a>
            </li>
        </ul>
    </div>
</div>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="doctorOverview" role="tabpanel">
        <div class="card mb-5 mb-xl-10">
            <div>
                <div class="card-body  border-top p-9">
                    <div class="row">
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.designation') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ $doctorData->doctorUser->designation ?? __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.phone') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($doctorData->doctorUser->phone) ? $doctorData->doctorUser->phone : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.appointment.doctor_department') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ getDoctorDepartment($doctorData->doctor_department_id) }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.qualification') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ $doctorData->doctorUser->qualification ?? __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.blood_group') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->blood_group))
                                    <span
                                        class="badge bg-light-{{ !empty($doctorData->doctorUser->blood_group) ? 'success' : 'danger' }}">
                                        {{ $doctorData->doctorUser->blood_group }} </span>
                                @else
                                    <span class="fs-5 text-gray-800">{{ __('messages.common.n/a') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name" class="pb-2 fs-5 text-gray-600">{{ __('messages.user.dob') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($doctorData->doctorUser->dob) ? \Carbon\Carbon::parse($doctorData->doctorUser->dob)->translatedFormat('jS M,Y') : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.doctor.specialist') }}</label>
                            <p>
                                <span class="fs-5 text-gray-800">{{ $doctorData->specialist }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.user.gender') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ $doctorData->doctorUser->gender != 1 ? __('messages.user.male') : __('messages.user.female') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.created_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($doctorData->doctorUser->created_at) ? $doctorData->doctorUser->created_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.common.updated_at') }}</label>
                            <p>
                                <span
                                    class="fs-5 text-gray-800">{{ !empty($doctorData->doctorUser->updated_at) ? $doctorData->doctorUser->updated_at->diffForHumans() : __('messages.common.n/a') }}</span>
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.facebook_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->facebook_url))
                                    <a href="{{ $doctorData->doctorUser->facebook_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->facebook_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.twitter_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->twitter_url))
                                    <a href="{{ $doctorData->doctorUser->twitter_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->twitter_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.instagram_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->instagram_url))
                                    <a href="{{ $doctorData->doctorUser->facebook_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->instagram_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-4 d-flex flex-column mb-md-10 mb-5">
                            <label for="name"
                                class="pb-2 fs-5 text-gray-600">{{ __('messages.linkedIn_url') }}</label>
                            <p>
                                @if (!empty($doctorData->doctorUser->linkedIn_url))
                                    <a href="{{ $doctorData->doctorUser->facebook_url }}"
                                        class="fs-5 text-primary-800 text-decoration-none">{{ $doctorData->doctorUser->linkedIn_url }}</a>
                                @else
                                    {{ __('messages.common.n/a') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="doctorCases" role="tabpanel">
        <livewire:doctor-cases-table docId="{{ $doctorData->id }}" />
    </div>

    <div class="tab-pane fade" id="doctorPatients" role="tabpanel">
        <livewire:doctor-patient-table docId="{{ $doctorData->id }}" />
    </div>
    <div class="tab-pane fade" id="doctorAppointments" role="tabpanel">
        <livewire:doctor-appointment-table docId="{{ $doctorData->id }}" />
    </div>
    <div class="tab-pane fade" id="doctorSchedules" role="tabpanel">
        <livewire:doctor-schedule-table docId="{{ $doctorData->id }}" />
    </div>
    <div class="tab-pane fade" id="doctorPayroll" role="tabpanel">
        <livewire:doctor-payroll-table docId="{{ $doctorData->id }}" />
    </div>
</div>
