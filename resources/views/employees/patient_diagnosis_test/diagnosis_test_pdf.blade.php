<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/logo.jpg') }}" type="image/png">
    <title>{{ __('messages.patient_diagnosis_test.patient_diagnosis_test') }} {{ __('messages.reports') }}</title>
    <link href="{{ asset('assets/css/diagnosis-test-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }
    </style>
</head>

<body>
    <table width="100%" class="mb-20">
        <tr>
            <td class="header-left">
                <div class="main-heading">
                    {{ __('messages.patient_diagnosis_test.patient_diagnosis_test') }} {{ __('messages.reports') }}
                </div>
                <div class="invoice-number font-color-gray">{{ __('messages.report_id') }}
                    #{{ $patientDiagnosisTest->report_number }}</div>
            </td>
            <td class="header-right">
                <div class="logo"><img width="100px" src="{{ $app_logo }}" alt=""></div>
                <div class="hospital-name">{{ $app_name }}</div>
                <div class="hospital-name font-color-gray">{{ $hospital_address }}</div>
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
                                    {{ $patientDiagnosisTest->patient->user->full_name }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $patientDiagnosisTest->patient->user->email }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.cell_no') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ !empty($patientDiagnosisTest->patient->user->phone) ? $patientDiagnosisTest->patient->user->phone : __('messages.common.n/a') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $patientDiagnosisTest->patient->user->gender == 0 ? 'Male' : 'Female' }}
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
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.ipd_patient_diagnosis.report_date') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ \Carbon\Carbon::parse($patientDiagnosisTest->created_at)->translatedFormat('jS M,Y g:i A') }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.doctor') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $patientDiagnosisTest->doctor->user->full_name }}
                                </td>
                            </tr>
                            <tr class="lh-2">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.diagnosis_category.diagnosis_category') }}:</label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $patientDiagnosisTest->category->name }}
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
                            <th>#</th>
                            <th>{{ __('messages.patient_diagnosis_test.diagnosis_property_name') }}</th>
                            <th>{{ __('messages.patient_diagnosis_test.diagnosis_property_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($patientDiagnosisTests))
                            @foreach ($patientDiagnosisTests as $key => $patientDiagnosisTest)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ str_replace('_', ' ', Str::title($patientDiagnosisTest->property_name)) }}
                                    </td>
                                    <td>{{ !empty($patientDiagnosisTest->property_value) ? $patientDiagnosisTest->property_value : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
