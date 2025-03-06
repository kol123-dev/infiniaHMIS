@php
    $settingValue = getSettingValue();
@endphp

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/logo.jpg') }}" type="image/png">
    <title>{{ __('messages.ipd_prescription') }}</title>
    <link href="{{ asset('assets/css/ipd-prescription-pdf.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <table width="100%">
        <tr>
            <td class="header-left">
                <div class="main-heading">{{ __('messages.ipd_patient_prescription.prescription_details') }}
                    {{ __('messages.reports') }}</div>
            </td>
            <td class="header-right">
                <div class="logo"><img width="100px" src="{{ getLogoUrl() }}" alt=""></div>
                <div>
                    <b>{{ __('messages.common.address') . ':' }}</b>{{ $settingValue['hospital_address']['value'] }}
                </div>
                <div>
                    <b>{{ __('messages.user.phone') . ':' }}</b>{{ $settingValue['hospital_phone']['value'] }}
                </div>
                <div class="hospital-name">
                    <b>{{ __('messages.user.email') . ':' }}</b>{{ $settingValue['hospital_email']['value'] }}
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <div class="header-note bg-light">
        <table class="table w-100">
            <tbody>
                <tr class="lh-2">
                    <label for="name"
                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient_prescription.header_note') }}:</label>
                </tr>
                <td>
                    <div class="ipdHeaderNoteData" id="ipdHeaderNoteData">
                        {!! !empty($ipdPrescription->header_note) ? nl2br(e($ipdPrescription->header_note)) : __('messages.common.n/a') !!}
                    </div>
                </td>
            </tbody>
        </table>
    </div>

    <div class="">
        <table class="table w-100">
            <tbody>
                <tr>
                    <td class="desc vertical-align-top bg-light">
                        <table class="table w-100">
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name" class="pb-2 fs-5 text-gray-600 font-weight-bold">
                                        {{ __('messages.patient.patient_details') }}:
                                    </label>
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.patient') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patient->user->full_name }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patient->user->email }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.phone') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patient->user->phone != null ? $ipdPrescription->patient->patient->user->phone : __('messages.common.n/a') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patient->user->gender == 0 ? __('messages.user.male') : __('messages.user.female') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.blood_donor.age') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patient->user->age }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.height') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->height != null ? $ipdPrescription->patient->height : __('messages.common.n/a') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.weight') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->weight != null ? $ipdPrescription->patient->weight : __('messages.common.n/a') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.bp') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->bp != null ? $ipdPrescription->patient->bp : __('messages.common.n/a') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:2%;">
                    </td>
                    <td class="text-end desc ms-auto vertical-align-top bg-light">
                        <table class="table w-100">
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.doctor_id') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->doctor->user->full_name }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.ipd_number') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->ipd_number }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.admission_date') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ date('jS M, Y H:i', strtotime($ipdPrescription->patient->admission_date)) }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.case.case_id') . ':' }}</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $ipdPrescription->patient->patientCase ? $ipdPrescription->patient->patientCase->case_id : __('messages.common.n/a') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <table width="100%">
        <tr>
            <td colspan="2">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.ipd_patient_prescription.category_id') }}</th>
                            <th>{{ __('messages.ipd_patient_prescription.medicine_id') }}</th>
                            <th>{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                            <th>{{ __('messages.medicine_bills.dose_interval') }}</th>
                            <th>{{ __('messages.ipd_patient_prescription.instruction') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($ipdPrescription->ipdPrescriptionItems))
                            @foreach ($ipdPrescription->ipdPrescriptionItems as $ipdPrescriptionItem)
                                <tr>
                                    <td>{{ $ipdPrescriptionItem->medicineCategory->name }} -
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $ipdPrescriptionItem->medicine->name }}</td>
                                    <td>{{ $ipdPrescriptionItem->dosage }}@if ($ipdPrescriptionItem->time == 0)
                                            ({{ __('messages.prescription.after_meal') }})
                                        @else
                                            ({{ __('messages.prescription.before_meal') }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ App\Models\Prescription::DOSE_INTERVAL[$ipdPrescriptionItem->dose_interval] ?? 'N\A' }}
                                    </td>
                                    <td>{!! !empty($ipdPrescriptionItem->instruction)
                                        ? nl2br(e($ipdPrescriptionItem->instruction))
                                        : __('messages.common.n/a') !!}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer-note bg-light">
        <table class="table w-100">
            <tbody>
                <tr class="lh-2">
                    <label for="name"
                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient_prescription.footer_note') }}:</label>
                </tr>
                <td>
                    <div class="ipdFooterNoteData" id="ipdFooterNoteData">
                        {!! !empty($ipdPrescription->footer_note) ? nl2br(e($ipdPrescription->footer_note)) : __('messages.common.n/a') !!}
                    </div>
                </td>
            </tbody>
        </table>
    </div>
</body>

</html>




{{-- @php
    $settingValue = getSettingValue();
@endphp
<div class="row">
    <div class="form-group col-sm-12 my-0">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ getLogoUrl() }}" class="w-75" alt="product-image" />
            </div>
            <div class="col-md-10 text-end">
                <p class="fw-bolder text-gray-800 fs-6 mb-2">{{ __('messages.common.address') . ':' }} <b
                        class="fw-bold fs-7 text-gray-600">{{ $settingValue['hospital_address']['value'] }}</b></p>
                <p class="fw-bolder text-gray-800 fs-6 mb-2">{{ __('messages.user.phone') . ':' }} <b
                        class="fw-bold fs-7 text-gray-600">{{ $settingValue['hospital_phone']['value'] }}</b></p>
                <p class="fw-bolder text-gray-800 fs-6 mb-2">{{ __('messages.user.email') . ':' }} <b
                        class="fw-bold fs-7 text-gray-600">{{ $settingValue['hospital_email']['value'] }}</b></p>
                <p class="fw-bolder text-gray-800 fs-6 mb-2">{{ __('messages.common.created_on') . ':' }} <b
                        class="fw-bold fs-7 text-gray-600">{{ date('jS M, Y H:i', strtotime($ipdPrescription->created_at)) }}</b>
                </p>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="form-group col-sm-12 my-0">
        <p class="my-0 mb-0 py-0 fw-bolder text-gray-800 fs-6" id="ipdHeaderNoteData">
            {!! !empty($ipdPrescription->header_note) ? nl2br(e($ipdPrescription->header_note)) : __('messages.common.n/a') !!}
        </p>
    </div>
</div>
<hr>
<div class="row g-5 mb-11">
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.ipd_number') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">{{ $ipdPrescription->patient->ipd_number }}</div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.bed_assign.patient_name') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">{{ $ipdPrescription->patient->patient->user->full_name }}</div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.user.email') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800 text-break">{{ $ipdPrescription->patient->patient->user->email }}
        </div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.user.phone') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->patient->user->phone != null ? $ipdPrescription->patient->patient->user->phone : __('messages.common.n/a') }}
        </div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.user.gender') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->patient->user->gender == 0 ? __('messages.user.male') : __('messages.user.female') }}
        </div>
    </div>
    <div class="col-sm-2 ">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.blood_donor.age') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">{{ $ipdPrescription->patient->patient->user->age }}</div>
    </div>
</div>
<div class="row g-5 mb-11">
    <div class="col-sm-2 ">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.admission_date') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ date('jS M, Y H:i', strtotime($ipdPrescription->patient->admission_date)) }}</div>
    </div>
    <div class="col-sm-2 ">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.case.case_id') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->patientCase ? $ipdPrescription->patient->patientCase->case_id : __('messages.common.n/a') }}
        </div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.doctor_id') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">{{ $ipdPrescription->patient->doctor->user->full_name }}</div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.height') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->height != null ? $ipdPrescription->patient->height : __('messages.common.n/a') }}
        </div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.weight') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->weight != null ? $ipdPrescription->patient->weight : __('messages.common.n/a') }}
        </div>
    </div>
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.bp') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">
            {{ $ipdPrescription->patient->bp != null ? $ipdPrescription->patient->bp : __('messages.common.n/a') }}
        </div>
    </div>
</div>
<div class="row g-5 mb-11">
    <div class="col-sm-2">
        <div class="fw-bold pe-10 text-gray-600 fs-7">{{ __('messages.ipd_patient.symptoms') . ':' }}</div>
        <div class="fw-bolder fs-6 text-gray-800">{!! $ipdPrescription->patient->symptoms != null
            ? $ipdPrescription->patient->symptoms
            : __('messages.common.n/a') !!}</div>
    </div>
</div>
<div class="flex-grow-1">
    <table class="table table-responsive-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer w-100 mb-3">
        <thead>
            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                <th class="min-w-175px pb-2">{{ __('messages.ipd_patient_prescription.category_id') }}</th>
                <th class="min-w-70px pb-2">{{ __('messages.ipd_patient_prescription.medicine_id') }}</th>
                <th class="min-w-70px pb-2">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                <th class="min-w-70px pb-2">{{ __('messages.medicine_bills.dose_interval') }}</th>
                <th class="min-w-180px pb-2">{{ __('messages.ipd_patient_prescription.instruction') }}</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-bold">
            @foreach ($ipdPrescription->ipdPrescriptionItems as $ipdPrescriptionItem)
                <tr class="fw-bolder text-gray-700 fs-5 text-end">
                    <td class="d-flex align-items-center pt-6">{{ $ipdPrescriptionItem->medicineCategory->name }}
                        - {{ $loop->iteration }}</td>
                    <td class="text-start pt-6">{{ $ipdPrescriptionItem->medicine->name }}</td>
                    <td class="text-start pt-6">
                        {{ $ipdPrescriptionItem->dosage }}
                        @if ($ipdPrescriptionItem->time == 0)
                            ({{ __('messages.prescription.after_meal') }})
                        @else
                            ({{ __('messages.prescription.before_meal') }})
                        @endif
                    </td>
                    <td class="text-start pt-6">
                        {{ App\Models\Prescription::DOSE_INTERVAL[$ipdPrescriptionItem->dose_interval] ?? 'N\A' }}
                    </td>
                    <td class="text-start pt-6">{!! !empty($ipdPrescriptionItem->instruction)
                        ? nl2br(e($ipdPrescriptionItem->instruction))
                        : __('messages.common.n/a') !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<hr class="py-0 my-0 mb-3">
<div class="row">
    <div class="form-group col-sm-12 my-0">
        <p class="my-0 mb-0 py-0 fw-bolder text-gray-800 fs-6" id="ipdFooterNoteData">
            {!! !empty($ipdPrescription->footer_note) ? nl2br(e($ipdPrescription->footer_note)) : __('messages.common.n/a') !!}
        </p>
    </div>
</div>
<hr> --}}
