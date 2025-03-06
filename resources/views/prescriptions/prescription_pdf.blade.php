<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>{{ __('messages.prescription.prescription') }}</title>
    <link href="{{ asset('assets/css/prescriptions-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }
    </style>
</head>

<body>
    <div class="px-30">
        <table>
            <tbody>
                <tr>
                    <td class="company-logo">
                        <img src="{{ $data['app_logo'] }}" alt="user">
                    </td>
                    <td class="px-30">
                        <h3 class="mb-0 lh-1">
                            {{ !empty($prescription['prescription']->doctor->doctorUser->full_name) ? $prescription['prescription']->doctor->doctorUser->full_name : '' }}
                        </h3>
                        <div class="fs-5 text-gray-600 fw-light mb-0 lh-1">
                            {{ !empty($prescription['prescription']->doctor->specialist) ? $prescription['prescription']->doctor->specialist : '' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="px-30 mb-20">
        <table class="table w-100 mb-20">
            <tbody>
                <tr>
                    @php
                        $doctorAddress = $prescription['prescription']->doctor->address;
                        $doctorUser = $prescription['prescription']->doctor->user;
                    @endphp
                    @if (!empty($doctorAddress->address1) || !empty($doctorAddress->address2) || !empty($doctorAddress->city))
                        <td class="desc vertical-align-top bg-light">
                            <div><b>{{ __('messages.user.address_details') }} :</b></div>
                            <div class="col-md-4 co-12 mt-md-0 mt-5">
                                @if (!empty($doctorAddress->address1))
                                    {{ $doctorAddress->address1 }}
                                @endif
                                @if (!empty($doctorAddress->address1) && !empty($doctorAddress->address2))
                                    ,
                                @endif
                                @if (!empty($doctorAddress->address2))
                                    {{ $doctorAddress->address2 }}
                                @endif
                                @if (!empty($doctorAddress->city))
                                    {{ $doctorAddress->address1 || $doctorAddress->address2 ? ',' : '' }}
                                    {{ $doctorAddress->city }}
                                @endif
                                @if (!empty($doctorAddress->zip))
                                    {{ $doctorAddress->city ? ',' : '' }}
                                    <br>
                                    {{ $doctorAddress->zip }}
                                @endif
                                <p class="text-gray-600 mb-3">
                                    {{ !empty($doctorUser->phone) ? $doctorUser->phone : '' }}
                                </p>
                                <p class="text-gray-600 mb-3">
                                    {{ !empty($doctorUser->email) ? $doctorUser->email : '' }}
                                </p>
                            </div>
                        </td>
                        <td style="width:2%;"></td>
                    @endif
                    <td class="text-end desc ms-auto vertical-align-top bg-light">
                        <table class="table w-100">
                            <div><b>{{ __('messages.patient.patient_details') }} :</b></div>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.bill.patient_name') }}:</label>

                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ !empty($prescription['prescription']->patient->patientUser->full_name) ? $prescription['prescription']->patient->patientUser->full_name : '' }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.case.date') }}:</label>

                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ !empty(\Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y')) ? \Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y') : '' }}
                                </td>
                            </tr>
                            @if ($prescription['prescription']->patient->user->dob)
                                <tr class="">
                                    <td>
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.blood_donor.age') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ \Carbon\Carbon::parse($prescription['prescription']->patient->user->dob)->diff(\Carbon\Carbon::now())->y }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="px-30">
        <table width="100%">
            <tr>
                <td colspan="2">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.prescription.physical_information_name') }}</th>
                                <th>{{ __('messages.prescription.physical_information_value') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($prescription['prescription']->food_allergies))
                                <tr>
                                    <td><b>{{ __('messages.prescription.food_allergies') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->food_allergies }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->tendency_bleed))
                                <tr>
                                    <td><b>{{ __('messages.prescription.tendency_bleed') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->tendency_bleed }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->heart_disease))
                                <tr>
                                    <td><b>{{ __('messages.prescription.heart_disease') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->heart_disease }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->high_blood_pressure))
                                <tr>
                                    <td><b>{{ __('messages.prescription.high_blood_pressure') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->high_blood_pressure }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->diabetic))
                                <tr>
                                    <td><b>{{ __('messages.prescription.diabetic') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->diabetic }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->surgery))
                                <tr>
                                    <td><b>{{ __('messages.prescription.surgery') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->surgery }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->accident))
                                <tr>
                                    <td><b>{{ __('messages.prescription.accident') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->accident }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->others))
                                <tr>
                                    <td><b>{{ __('messages.prescription.others') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->others }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->medical_history))
                                <tr>
                                    <td><b>{{ __('messages.prescription.medical_history') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->medical_history }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->current_medication))
                                <tr>
                                    <td><b>{{ __('messages.prescription.current_medication') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->current_medication }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->female_pregnancy))
                                <tr>
                                    <td><b>{{ __('messages.prescription.female_pregnancy') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->female_pregnancy }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->breast_feeding))
                                <tr>
                                    <td><b>{{ __('messages.prescription.breast_feeding') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->breast_feeding }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->plus_rate))
                                <tr>
                                    <td><b>{{ __('messages.prescription.plus_rate') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->plus_rate }}
                                    </td>
                                </tr>
                            @endif

                            @if (!empty($prescription['prescription']->temperature))
                                <tr>
                                    <td><b>{{ __('messages.prescription.temperature') }}:</td></b>
                                    <td>
                                        {{ $prescription['prescription']->temperature }}
                                    </td>
                                </tr>
                            @endif

                            @if ($prescription['prescription']->problem_description != null)
                                <tr>
                                    <td>
                                        <b>{{ __('messages.prescription.problem') }}:</b>
                                    </td>
                                    <td>{{ $prescription['prescription']->problem_description }}</td>
                                </tr>
                            @endif

                            @if ($prescription['prescription']->test != null)
                                <tr>
                                    <td>
                                        <b>{{ __('messages.prescription.test') }}:</b>
                                    </td>
                                    <td>{{ $prescription['prescription']->test }}</td>
                                </tr>
                            @endif

                            @if ($prescription['prescription']->advice != null)
                                <tr>
                                    <td>
                                        <b>{{ __('messages.prescription.advice') }}:</b>
                                    </td>
                                    <td>{{ $prescription['prescription']->advice }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    {{-- @if ($prescription['prescription']->problem_description != null)
        <div class="mb-20 px-30">
            <div class="heading">
                <div class="fw-6">{{ __('messages.prescription.problem') }}:</div>
            </div>
            <div class="">
                <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->problem_description }}</p>
            </div>
        </div>
    @endif
    @if ($prescription['prescription']->test != null)
        <div class="mb-20 px-30">
            <div class="heading">
                <div class="fw-6">{{ __('messages.prescription.test') }}:</div>
            </div>
            <div class="">
                <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->test }}</p>
            </div>
        </div>
    @endif
    @if ($prescription['prescription']->advice != null)
        <div class="mb-20 px-30">
            <div class="heading">
                <div class="fw-6">{{ __('messages.prescription.advice') }}:</div>
            </div>
            <div class="">
                <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->advice }}</p>
            </div>
        </div>
    @endif --}}
    <div class="px-30">
        <table class="items-table">
            <thead>
                <tr>
                    <th scope="col">{{ __('messages.prescription.medicine_name') }}</th>
                    <th scope="col">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                    <th scope="col">{{ __('messages.prescription.duration') }}</th>
                    <th scope="col">{{ __('messages.medicine_bills.dose_interval') }}</th>
                </tr>
            </thead>
            <tbody>
                @if (empty($medicines))
                    {{ __('messages.common.n/a') }}
                @else
                    @foreach ($prescription['prescription']->getMedicine as $medicine)
                        @foreach ($medicine->medicines as $medi)
                            <tr>
                                <td class="py-4 border-bottom-0">{{ $medi->name }}</td>
                                <td class="py-4 border-bottom-0">
                                    {{ $medicine->dosage }}

                                    @if ($medicine->time == 0)
                                        {{ __('messages.prescription.after_meal') }}
                                    @else
                                        {{ __('messages.prescription.before_meal') }}
                                    @endif

                                </td>
                                <td class="py-4 border-bottom-0">{{ $medicine->day }}
                                    {{ __('messages.appointment.day') }}
                                </td>
                                <td class="py-4 border-bottom-0">
                                    {{ App\Models\Prescription::DOSE_INTERVAL[$medicine->dose_interval] }}</td>
                            </tr>
                        @break
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="px-30">
    <table width="100%">
        <tr>
            <td class="header-left">
                @if ($prescription['prescription']->next_visit_qty != null)
                    <h4>
                        {{ __('messages.prescription.next_visit') }} :
                        {{ $prescription['prescription']->next_visit_qty }}
                        @if ($prescription['prescription']->next_visit_time == 0)
                            {{ __('messages.prescription.days') }}
                        @elseif($prescription['prescription']->next_visit_time == 1)
                            {{ __('messages.month') }}
                        @else
                            {{ __('messages.year') }}
                        @endif
                    </h4>
                @endif
            </td>
            <td class="header-right">
                <h3 class="mb-0 lh-1">
                    {{ !empty($prescription['prescription']->doctor->doctorUser->full_name) ? $prescription['prescription']->doctor->doctorUser->full_name : '' }}
                </h3>
                <div class="fs-5 text-gray-600 fw-light mb-0 lh-1">
                    {{ !empty($prescription['prescription']->doctor->specialist) ? $prescription['prescription']->doctor->specialist : '' }}
                </div>
            </td>
        </tr>
    </table>
</div>
</div>
</body>

</html>
