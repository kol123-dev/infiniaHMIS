@extends('web.layouts.front')
@section('title')
    {{ __('messages.doctor.doctor_details') }}
@endsection
@section('page_css')
    {{--    <link rel="stylesheet" href="{{ mix('web_front/css/doctors.css') }}"> --}}
@endsection
@section('content')
    <!-- start hero section -->
    <section
        class="hero-section position-relative p-t-60 border-bottom-right-rounded border-bottom-left-rounded bg-gray overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 {{App::getLocale() == 'ar' ? 'text-lg-end' : 'text-lg-start'}} text-center">
                    <div class="hero-content">
                        <h1 class="mb-3 pb-1">
                            {{ __('messages.doctor.doctor_details') }}
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-lg-start justify-content-center mb-lg-0 mb-5">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}">{{ __('messages.web_home.home') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('messages.doctor.doctor_details') }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end text-center">
                    <img src="{{ asset('web_front/images/page-banner/Doctors.png') }}" alt="Infy Care" class="img-fluid" />
                </div>
            </div>
        </div>
    </section>
    <!-- end hero section -->

    {{-- Start Doctor details seciton --}}
    <section class="container p-sm-5 p-3">
        <div class="card mb-3 p-lg-5 p-md-4 p-3">
            <div class="row justify-content-center m-0">
                <div class="col-md-3">
                    <img src="{{ $doctorDetails->doctorUser->image_url }}" style="height:250px" class="img-fluid rounded"
                        alt="Doctor Details">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="row">
                            <div>
                                <h4>{{ $doctorDetails->doctorUser->fullname }}</h4>
                                <h5>{{ __('messages.user.email') }}</h5>
                                <p>
                                    <i class="fa-solid fa-envelope text-success px-2"></i>
                                    {{ $doctorDetails->doctorUser->email }}
                                </p>
                                <h5> {{ __('messages.user.qualification') }}</h5>
                                <p>
                                    @if (!empty($doctorDetails->doctorUser->qualification))
                                        <i class="fa-solid fa-graduation-cap text-success px-2"></i>
                                        {{ $doctorDetails->doctorUser->qualification }}
                                    @else
                                        {{ __('messages.common.n/a') }}
                                    @endif
                                </p>
                                <h5>{{ __('messages.doctor.specialist') }}</h5>
                                <p>
                                    <i class="fas fa-user-md text-success px-2"></i>
                                    {{ $doctorDetails->specialist }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center container bg-white shadow-lg rounded mt-sm-5 mt-4 ">
                    <nav class="pt-lg-5 pt-4  ">
                        <ul class="nav nav-pills mb-1 border-0 flex-nowrap overflow-auto">
                            @if (!empty($doctorDetails->description))
                                <li class="nav-item text-nowrap mb-2">
                                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview"
                                        type="button">{{ __('messages.about_us') }}</a>
                                </li>
                            @endif
                            <li class="nav-item text-nowrap mb-2">
                                <a class="nav-link {{ empty($doctorDetails->description) ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#schedule"
                                    type="button">{{ __('messages.schedules') }}</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="tab-content text-start p-3">
                        <div class="tab-pane fade active show" id="overview" role="tabpanel">
                            <div class="row">
                                @if (!empty($doctorDetails->description))
                                    <div class="col-md-12">
                                        <h5>{{ __('messages.common.description') }}</h5>
                                        <p class="fw-normal pb-3 text-break">{{ $doctorDetails->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade overflow-auto {{ empty($doctorDetails->description) ? 'active show' : '' }}"
                            id="schedule" role="tabpanel">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.schedule.available_on') }}</th>
                                        <th>{{ __('messages.schedule.available_from') }}</th>
                                        <th>{{ __('messages.schedule.available_to') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($doctorSchedule as $schedule)
                                        <tr>
                                            <td>{{ __('messages.schedule_weekday.'.$schedule->available_on) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->available_from)->format('h:i A') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->available_to)->format('h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End Doctor details section --}}
@endsection
