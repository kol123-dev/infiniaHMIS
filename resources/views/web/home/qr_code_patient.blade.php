@extends('web.layouts.front')
@section('title')
    {{ __('messages.patient.patient_details') }}
@endsection
@section('content')
    <section
        class="hero-section position-relative p-t-60 border-bottom-right-rounded border-bottom-left-rounded bg-gray overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-lg-start text-center">
                    <div class="hero-content">
                        <h1 class="mb-3 pb-1">
                            {{ __('messages.patient.patient_details') }}
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-lg-start justify-content-center mb-lg-0 mb-5">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}">{{ __('messages.web_home.home') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('messages.patient.patient_details') }}
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

    <section class="container p-sm-5 p-3">
        <div class="card mb-3 p-sm-4 p-2">
            <div class="row justify-content-center mx-sm-3 mx-1">
                <div class="col-md-2 pe-4 image image-medium mt-2">
                    <img class="rounded" src="{{ $data->patientUser->image_url }}" alt="image" />
                </div>
                <div class="col-lg-5 col-md-12 mt-2">
                    <div class="card-body">
                        <div class="row">
                            <div>
                                <h4><a href="#"
                                        class="text-success text-decoration-none fs-5">{{ $data->patientUser->full_name }}</a>
                                </h4>
                                </p>
                                <p>
                                    <span class="card-text fs-6 text-success">
                                        <i class="fa-solid fa-envelope me-1"></i>
                                        <a href="mailto:{{ $data->patientUser->email }}"
                                            class="text-success text-decoration-none fs-5">
                                            {{ $data->patientUser->email }}
                                        </a>
                                    </span>
                                </p>
                                <p>
                                    <span class="card-text fs-6 text-success">
                                        @if (
                                            !empty($data->address->address1) ||
                                                !empty($data->address->address2) ||
                                                !empty($data->address->city) ||
                                                !empty($data->address->zip))
                                            <i class="fa-solid fa-location-dot text-gray-600 me-3"></i>
                                        @endif
                                        <span class="text-start">
                                            {{ !empty($data->address->address1) ? $data->address->address1 : '' }}{{ !empty($data->address->address2) ? (!empty($data->address->address1) ? ',' : '') : '' }}
                                            {{ empty($data->address->address1) || !empty($data->address->address2) ? (!empty($data->address->address2) ? $data->address->address2 : '') : '' }}
                                            {{ empty($data->address->address1) && empty($data->address->address2) ? '' : '' }}{{ !empty($data->address->city) ? ',' . $data->address->city : '' }}{{ !empty($data->address->zip) ? ',' . $data->address->zip : '' }}
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="row justify-content-end pe-3">
                        <div class="col-md-9 col-12 mb-6 mb-md-0">
                            <div class="shadow rounded p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-primary ps-2">
                                        {{ !empty($data->cases) ? $data->cases->count() : 0 }}</h2>
                                </div>
                                <div>
                                    <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                        {{ __('messages.patient.total_cases') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end pe-3 pt-2">
                        <div class="col-md-9 col-12 mb-6 mb-md-0">
                            <div class="shadow rounded p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-primary ps-2">
                                        {{ !empty($data->admissions) ? $data->admissions->count() : 0 }}</h2>
                                </div>
                                <div>
                                    <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                        {{ __('messages.patient.total_admissions') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end pe-3 pt-2">
                        <div class="col-md-9 col-12">
                            <div class="shadow rounded p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="text-primary ps-2">
                                        {{ !empty($data->appointments) ? $data->appointments->count() : 0 }}</h2>
                                </div>
                                <div>
                                    <h3 class="fs-5 fw-light text-gray-600 mb-0">
                                        {{ __('messages.patient.total_appointments') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (Auth::check())
                    <div class="row text-center container bg-white shadow-lg rounded mt-5 p-sm-3 p-0">
                        <nav class="pt-sm-5 pt-4">
                            <ul class="nav nav-pills mb-3 border-0  flex-nowrap overflow-auto">
                                <li class="nav-item">
                                    <a class="nav-link active text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#poverview" role="tab" aria-controls="patients"
                                        aria-selected="false">
                                        {{ __('messages.overview') }}
                                    </a>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pcases" type="button" role="tab" aria-controls="patients"
                                        aria-selected="false">
                                        {{ __('messages.cases') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#padmissions" type="button" role="tab" aria-controls="patients"
                                        aria-selected="false">
                                        {{ __('messages.patient_admissions') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pappointments" type="button" role="tab"
                                        aria-controls="patients" aria-selected="false">
                                        {{ __('messages.appointments') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pbills" type="button" role="tab" aria-controls="patients"
                                        aria-selected="false">
                                        {{ __('messages.bills') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pinvoices" type="button" role="tab"
                                        aria-controls="patients" aria-selected="false">
                                        {{ __('messages.invoices') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pAdvancedPayments" type="button" role="tab"
                                        aria-controls="patients" aria-selected="false">
                                        {{ __('messages.advanced_payments') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pDocument" type="button" role="tab"
                                        aria-controls="patients" aria-selected="false">
                                        {{ __('messages.documents') }}
                                    </button>
                                </li>
                                <li class="nav-item position-relative me-7 mb-3" role="presentation">
                                    <button class="nav-link text-nowrap" id="patients-tab" data-bs-toggle="tab"
                                        data-bs-target="#pVaccinated" type="button" role="tab"
                                        aria-controls="patients" aria-selected="false">
                                        {{ __('messages.vaccinations') }}
                                    </button>
                                </li>
                            </ul>
                        </nav>
                        <div class="tab-content text-start mb-sm-5 mb-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="poverview" role="tabpanel"
                                aria-labelledby="overview-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            @if (!empty($data->patientUser->phone))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.user.phone') . ':' }}</h5>
                                                    <p class="fw-normal pb-sm-4 pb-3">
                                                        {{ $data->patientUser->phone }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($data->patientUser->gender))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.user.gender') . ':' }}</h5>
                                                    <p class="fw-normal pb-sm-4 pb-3">
                                                        {{ $data->patientUser->gender != 1 ? __('messages.user.male') : __('messages.user.female') }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($data->patientUser->blood_group))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.user.blood_group') . ':' }}
                                                    </h5>
                                                    <p class="fw-normal pb-sm-4 pb-3">
                                                        {{ $data->patientUser->blood_group }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($data->patientUser->dob))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.user.dob') . ':' }}</h5>
                                                    <p class="fw-normal pb-sm-4 pb-3">
                                                        {{ \Carbon\Carbon::parse($data->patientUser->dob)->translatedFormat('jS M, Y') }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($data->patientUser->created_at))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.common.created_at') . ':' }}
                                                    </h5>
                                                    <p class="fw-normal pb-sm-4 pb-3">
                                                        {{ $data->patientUser->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($data->patientUser->updated_at))
                                                <div class="col-md-6 ps-sm-3">
                                                    <h5 class="details-text">{{ __('messages.common.updated_at') . ':' }}
                                                    </h5>
                                                    <p class="fw-normal pb-sm-4 pb-0">
                                                        {{ $data->patientUser->updated_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pcases" role="tabpanel" aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">{{ __('messages.case.case_id') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.case.doctor') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.case.case_date') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.case.fee') }}</th>
                                                <th scope="col" class="p-3">{{ __('messages.user.status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['patientCases'] as $patientCase)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">#{{ $patientCase->case_id }}</th>
                                                    <td class="p-3">
                                                        {{ $patientCase->doctor->doctorUser->full_name }}
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($patientCase->date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">{{ checkNumberFormat($patientCase->fee, strtoupper(getCurrentCurrency())) }}</td>
                                                    <td class="p-3">
                                                        @if ($patientCase->status)
                                                            <span
                                                                class="badge bg-success">{{ __('messages.common.active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger">{{ __('messages.common.de_active') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="padmissions" role="tabpanel" aria-labelledby="patients-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.patient_admission.patient_admission_id') }}</th>
                                                <th scope="col" class="p-3">{{ __('messages.case.doctor') }}
                                                </th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.bill.admission_date') }}
                                                </th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.bill.discharge_date') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.user.status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['patientAdmissions'] as $patientAdmission)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">#{{ $patientAdmission->patient_admission_id }}
                                                    </th>
                                                    <td class="p-3">
                                                        {{ $patientAdmission->doctor->doctorUser->full_name }}
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($patientAdmission->admission_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($patientAdmission->discharge_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">
                                                        @if ($patientCase->status)
                                                            <span
                                                                class="badge bg-success">{{ __('messages.common.active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger">{{ __('messages.common.de_active') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pappointments" role="tabpanel"
                                aria-labelledby="appointments-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">{{ __('messages.case.doctor') }}
                                                </th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.doctor_department.doctor_department') }}</th>
                                                <th scope="col" class="p-3">{{ __('messages.sms.date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['appointments'] as $appointment)
                                                <tr class="shadow-sm border-0">
                                                    <td class="p-3">
                                                        {{ $appointment->doctor->doctorUser->full_name }}
                                                    </td>
                                                    <td class="p-3">{{ $appointment->doctor->department->title }}
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($appointment->opd_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pbills" role="tabpanel" aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">{{ __('messages.bill.bill_id') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.bill.bill_date') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.bill.amount') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['bills'] as $bill)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">#{{ $bill->bill_id }}</th>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($bill->bill_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">{{ getCurrencyFormat($bill->amount) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pinvoices" role="tabpanel" aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.invoice.invoice_id') }}
                                                </th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.invoice.invoice_date') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.user.status') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.bill.amount') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['invoices'] as $invoice)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">#{{ $invoice->invoice_id }}</th>
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">
                                                        @if ($invoice->status)
                                                            <span
                                                                class="badge bg-success">{{ __('messages.invoice.paid') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger">{{ __('messages.invoice.not_paid') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3">{{ getCurrencyFormat($invoice->amount) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pAdvancedPayments" role="tabpanel"
                                aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.advanced_payment.receipt_no') }}</th>
                                                <th scope="col" class="p-3">{{ __('messages.incomes.date') }}
                                                </th>
                                                <th scope="col" class="p-3">{{ __('messages.bill.amount') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['advancePayments'] as $advancePayment)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">#{{ $advancePayment->receipt_no }}</th>
                                                    </td>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($advancePayment->date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="p-3">
                                                        {{ getCurrencyFormat($advancePayment->amount) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pDocument" role="tabpanel" aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.document.document_type') }}</th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.bed_type.title') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['documents'] as $document)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">{{ $document->documentType->name }}</th>
                                                    <td class="p-3">{{ $document->title }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pVaccinated" role="tabpanel" aria-labelledby="cases-tab">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="bg-gray text-success">
                                            <tr>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.vaccinated_patient.vaccination_name') }}</th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.vaccinated_patient.serial_no') }}</th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.vaccinated_patient.does_no') }}
                                                </th>
                                                <th scope="col" class="p-3">
                                                    {{ __('messages.vaccinated_patient.dose_given_date') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-success">
                                            @forelse ($data['vaccinations'] as $vaccination)
                                                <tr class="shadow-sm border-0">
                                                    <th class="p-3">{{ $vaccination->vaccination->name }}</th>
                                                    <td class="p-3">{{ $vaccination->vaccination_serial_number }}
                                                    </td>
                                                    <th class="p-3">{{ $vaccination->dose_number }}</th>
                                                    <td class="p-3">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($vaccination->dose_given_date)->translatedFormat('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12">
                                                        <span
                                                            class="d-flex justify-content-center">{{ __('messages.no_data_available') }}</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
