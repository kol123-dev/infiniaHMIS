<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>{{ __('messages.ipd_patient.discharge_slip') }}</title>
    <link href="{{ asset('assets/css/bill-pdf.css') }}" rel="stylesheet" type="text/css" />
    @if (getCurrentCurrency() == 'inr')
        <style>
            body {
                font-family: DejaVu Sans, sans-serif !important;
            }
        </style>
    @endif
    <style>
        .text-end {
            text-align: right !important;
        }

        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        @page {
            margin: 20px 0 !important;
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 50% !important;
        }

        .text-end {
            text-align: right !important;

        }

        .text-center {
            text-align: center !important;

        }

        .ms-auto {
            margin-left: auto !important;
        }

        .px-30 {
            padding-left: 30px;
            padding-right: 30px;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .lh-1 {
            line-height: 1.5 !important;
        }

        .company-logo {
            margin: 0 auto;
        }

        .company-logo img {
            width: auto;
            height: 80px;
        }

        .vertical-align-top {
            vertical-align: top !important;
        }

        .desc {
            padding: 10px;
            border-radius: 10px;
            width: 48%;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        hr {
            margin: 15px 0px;
            color: #f8f9fa;
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .fw-6 {
            font-weight: bold;
        }

        .mb-20 {
            margin-bottom: 15px;
        }

        .heading {
            padding: 10px;
            background-color: #f8f9fa;
            width: 250px;
        }

        .lh-2 {
            line-height: 1.5 !important;
        }

        .text-start {
            text-align: left !important;
        }

        .table-head {
            font-size: 18px !important;
            padding-bottom: 12px !important;
            font-weight: bold;
        }

        .description {
            word-break: break-word;
            width: 45% !important;
        }

        .instruction {
            word-break: break-word;
            width: 20% !important;
        }

        .main-heading {
            font-size: 28px !important;
            width: 300px !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
        }
    </style>
</head>

<body>
    {{-- <table width="100%" class="mb-20">
        <table width="100%">
            <tr>
                <td class="header-left">
                    <div class="main-heading">{{ __('messages.ipd_patient.discharge_slip') }}</div>
                </td>
                <td class="header-right">
                    <div class="logo"><img width="100px" src="{{ $setting['app_logo'] }}" alt=""></div>
                    <div class="hospital-name">{{ $setting['app_name'] }}</div>
                    <div class="hospital-name font-color-gray">{{ $setting['hospital_address'] }}</div>
                </td>
            </tr>
        </table>
        <hr>
        <div class="">
            <table class="table w-100">
                <tbody>
                    <tr>
                        <td class="desc vertical-align-top bg-light">
                            <table class="table w-100">
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name" class="pb-2 fs-5 text-gray-600 font-weight-bold">
                                            {{ __('messages.patient.patient_details') }}
                                        </label>
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.ipd_number') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->ipd_number }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.patient') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->email }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.cell_no') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->patient->user->phone) ? $bill['ipd_patient_department']->patient->user->phone : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->gender == 0 ? __('messages.user.male') : __('messages.user.female') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.blood_group') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->patient->user->blood_group) ? $bill['ipd_patient_department']->patient->user->blood_group : 'N/A' }}
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
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.case_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->case_id) ? '#' . $bill['ipd_patient_department']->patientCase->case_id : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.doctor_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->doctor->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.admission_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ date('jS M, Y m:s', strtotime($bill['ipd_patient_department']->admission_date)) }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.bed_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->bed->name }}
                                        ({{ $bill['ipd_patient_department']->bedType->title }})
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.height') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->height) ? $bill['ipd_patient_department']->height : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.weight') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->weight) ? $bill['ipd_patient_department']->weight : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </td>
        </tr>
        @if ($diagnosis->count() > 0)
            <tr>
                <td colspan="2">
                    <table class="table w-100 items-table" width="100%">
                        <div class="table-head">{{ __('messages.ipd_diagnosis') }}</div>
                        <thead class="table-dark">
                            <tr>
                                <th class="text-left">{{ __('messages.ipd_patient_diagnosis.report_type') }}</th>
                                <th>{{ __('messages.ipd_patient_diagnosis.report_date') }}</th>
                                <th class="description text-center">
                                    {{ __('messages.ipd_patient_diagnosis.description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diagnosis as $diagnosis)
                                <tr>
                                    <td>{{ $diagnosis->report_type }}</td>
                                    <td>{{ \Carbon\Carbon::parse($diagnosis->report_date)->format('d/m/Y') }}</td>
                                    <td class="description">{{ $diagnosis->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
        @if ($instructions->count() > 0)
            <tr>
                <td colspan="2">
                    <table class="table w-100 items-table mb-20" width="100%">
                        <div class="table-head">{{ __('messages.ipd_consultant_register') }}</div>
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">{{ __('messages.ipd_patient_consultant_register.doctor_id') }}</th>
                                <th scope="col">{{ __('messages.ipd_patient_consultant_register.applied_date') }}
                                </th>
                                <th scope="col" class="text-center">
                                    {{ __('messages.ipd_patient_consultant_register.instruction_date') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($instructions as $instruction)
                                <tr>
                                    <td>{{ $instruction->doctor->doctorUser->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($instruction->applied_date)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($instruction->instruction_date)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
        @if ($ipdPrescriptions->count() > 0)
            <tr>
                <td colspan="2">
                    <table class="table w-100 items-table mb-20" width="100%">
                        <div class="table-head">{{ __('messages.ipd_prescription') }}</div>
                        <thead class="table-dark">
                            <tr>
                                <th class="col">{{ __('messages.ipd_patient_prescription.category_id') }}</th>
                                <th class="col">{{ __('messages.ipd_patient_prescription.medicine_id') }}</th>
                                <th class="col">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                                <th class="col">{{ __('messages.medicine_bills.dose_interval') }}</th>
                                <th class="col">{{ __('messages.ipd_patient_prescription.instruction') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ipdPrescriptions as $ipdPrescription)
                                @foreach ($ipdPrescription->ipdPrescriptionItems as $ipdPrescriptionItem)
                                    <tr>
                                        <td class="d-flex align-items-center pt-6">
                                            {{ $ipdPrescriptionItem->medicineCategory->name }}
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
                                        <td class="text-start pt-6 instruction">{!! !empty($ipdPrescriptionItem->instruction)
                                            ? nl2br(e($ipdPrescriptionItem->instruction))
                                            : __('messages.common.n/a') !!}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
    </table> --}}
    <div width="100%" class="mb-20">
        <table width="100%">
            <tr>
                <td class="header-left">
                    <div class="main-heading">{{ __('messages.ipd_patient.discharge_slip') }}</div>
                </td>
                <td class="header-right">
                    <div class="logo"><img width="100px" src="{{ $setting['app_logo'] }}" alt=""></div>
                    <div class="hospital-name">{{ $setting['app_name'] }}</div>
                    <div class="hospital-name font-color-gray">{{ $setting['hospital_address'] }}</div>
                </td>
            </tr>
        </table>
        <hr>
        <div class="">
            <table class="table w-100">
                <tbody>
                    <tr>
                        <td class="desc vertical-align-top bg-light">
                            <table class="table w-100">
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name" class="pb-2 fs-5 text-gray-600 font-weight-bold">
                                            {{ __('messages.patient.patient_details') }}
                                        </label>
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.ipd_number') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->ipd_number }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.patient') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->email }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.cell_no') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->patient->user->phone) ? $bill['ipd_patient_department']->patient->user->phone : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->patient->user->gender == 0 ? __('messages.user.male') : __('messages.user.female') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.blood_group') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->patient->user->blood_group) ? $bill['ipd_patient_department']->patient->user->blood_group : 'N/A' }}
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
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.case_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->case_id) ? '#' . $bill['ipd_patient_department']->patientCase->case_id : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.doctor_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->doctor->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.admission_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ date('jS M, Y m:s', strtotime($bill['ipd_patient_department']->admission_date)) }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.bed_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill['ipd_patient_department']->bed->name }}
                                        ({{ $bill['ipd_patient_department']->bedType->title }})
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.height') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->height) ? $bill['ipd_patient_department']->height : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient.weight') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill['ipd_patient_department']->weight) ? $bill['ipd_patient_department']->weight : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($diagnosis->count() > 0)
            <div class="table-head" style="font-size: 18px;font-weight:bold;margin-top:20px;">
                {{ __('messages.ipd_diagnosis') }}</div>
            <table class="table w-100 items-table" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th class="text-left">{{ __('messages.ipd_patient_diagnosis.report_type') }}</th>
                        <th>{{ __('messages.ipd_patient_diagnosis.report_date') }}</th>
                        <th class="text-center description">{{ __('messages.ipd_patient_diagnosis.description') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diagnosis as $diagnosis)
                        <tr>
                            <td>{{ $diagnosis->report_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($diagnosis->report_date)->format('d/m/Y') }}</td>
                            <td class="description text-justify">{{ $diagnosis->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if ($instructions->count() > 0)
            <div class="table-head" style="font-size: 18px;font-weight:bold;margin-top:20px;">
                {{ __('messages.ipd_consultant_register') }}</div>
            <table class="table w-100 items-table mb-20" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">{{ __('messages.ipd_patient_consultant_register.doctor_id') }}</th>
                        <th scope="col">{{ __('messages.ipd_patient_consultant_register.applied_date') }}
                        </th>
                        <th scope="col" class="text-center">
                            {{ __('messages.ipd_patient_consultant_register.instruction_date') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructions as $instruction)
                        <tr>
                            <td>{{ $instruction->doctor->doctorUser->full_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($instruction->applied_date)->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($instruction->instruction_date)->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if ($ipdPrescriptions->count() > 0)
            <div>
                <div class="table-head" style="font-size: 18px;font-weight:bold;margin-top:20px;">
                    {{ __('messages.prescriptions') }}</div>
                <table class="table w-100 items-table mb-20" width="100%">
                    <thead>
                        <tr class="table-dark" style="background-color:#2f353a;color:#fff;padding-top:10px;">
                            <th class="col">{{ __('messages.ipd_patient_prescription.category_id') }}</th>
                            <th class="col">{{ __('messages.ipd_patient_prescription.medicine_id') }}</th>
                            <th class="col">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                            <th class="col">{{ __('messages.medicine_bills.dose_interval') }}</th>
                            <th class="col instruction">{{ __('messages.ipd_patient_prescription.instruction') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ipdPrescriptions as $ipdPrescription)
                            @foreach ($ipdPrescription->ipdPrescriptionItems as $ipdPrescriptionItem)
                                <tr>
                                    <td class="d-flex align-items-center pt-6">
                                        {{ $ipdPrescriptionItem->medicineCategory->name }}
                                        - {{ $loop->iteration }}</td>
                                    <td class="text-start pt-6">{{ $ipdPrescriptionItem->medicine->name }}
                                    </td>
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
                                    <td class="text-start pt-6 instruction">{!! !empty($ipdPrescriptionItem->instruction)
                                        ? nl2br(e($ipdPrescriptionItem->instruction))
                                        : __('messages.common.n/a') !!}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>

</html>
