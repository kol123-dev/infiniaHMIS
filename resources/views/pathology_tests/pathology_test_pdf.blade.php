<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>{{ __('messages.new_change.pathology_test_report') }}</title>
    <link href="{{ asset('assets/css/prescription-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        body {
            font-family: "Lato", sans-serif;
            padding: 30px;
            font-size: 14px;
        }

        .header-right {
            text-align: right;
            vertical-align: top;
        }

        .header-left {
            text-align: left;
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border: 0;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .items-table thead {
            background: #2f353a;
            color: #fff;
        }

        .items-table td,
        .items-table th {
            padding: 8px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            text-align: left;
            vertical-align: top;
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
            margin: 15px 20px;
            color: #f6fcff;
            background-color: #f6fcff;
            border-color: #f6fcff;
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

        .mb-2 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="px-30">
        <table>
            <tbody>
                <tr style="color:#2c3e50;">
                    <td class="company-logo">
                        <img src="{{ $data['logo']['app_logo'] }}" alt="user">
                    </td>
                    <td class="px-30">
                        <h3 class="mb-0 lh-1">
                            {{ __('messages.new_change.pathology_test_report') }}
                        </h3>
                        <div class="fs-5 text-gray-600 fw-light mb-0 lh-1">
                            {{ getAppName() }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="px-30">
        <table class="table w-100 mb-20">
            <tbody>
                <tr>
                    <td class="desc vertical-align-top bg-light" style="background-color: #f6fcff;color:#2c3e50;">
                        <div class="col-md-4 co-12 mt-md-0 mt-5">
                            <table class="table w-100">
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.bed_status.patient_name') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']->patient->user->fullname ?? __('messages.common.n/a')}}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.test_name') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']['test_name'] }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.test_type') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']['test_type'] }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.short_name') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']['short_name'] }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.method') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']['method'] }}
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.charge_category') }}:
                                        </label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $data['pathologyTest']->chargecategory->name }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td style="width:2%;">
                    </td>
                    <td class="text-end desc ms-auto vertical-align-top bg-light"
                        style="background-color: #f6fcff;
                    color:#2c3e50;">
                        <table class="table w-100">
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.category_name') }}:
                                    </label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $data['pathologyTest']->pathologycategory->name }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.unit') }}:
                                    </label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $data['pathologyTest']->unit }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.report_days') }}:
                                    </label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $data['pathologyTest']->report_days }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.standard_charge') }}:
                                    </label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ getCurrencyFormat($data['pathologyTest']->standard_charge) }}
                                </td>
                            </tr>
                            <tr class="">
                                <td class="">
                                    <label for="name"
                                        class="pb-2 fs-5 text-gray-600 me-1">{{ __('messages.pathology_test.subcategory') }}:
                                    </label>
                                </td>
                                <td class="text-end fs-5 text-gray-800">
                                    {{ $data['pathologyTest']->subcategory }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="px-30">
        <table class="items-table">
            <thead style="background-color: #3dc1d3;color:#fff;">
                <tr>
                    <th scope="col">{{ __('messages.new_change.parameter_name') }}</th>
                    <th scope="col">{{ __('messages.new_change.patient_result') }}</th>
                    <th scope="col">{{ __('messages.new_change.reference_range') }}</th>
                    <th scope="col">{{ __('messages.item.unit') }}</th>
                </tr>
            </thead>
            <tbody style="background-color: #f6fcff;color:#2c3e50;">
                @if (empty($data['pathologyParameterItems']))
                    {{ __('messages.common.n/a') }}
                @else
                    @foreach ($data['pathologyParameterItems'] as $pathologyParameterItem)
                        <tr>
                            <td class="py-4 border-bottom-0">
                                {{ $pathologyParameterItem->pathologyParameter->parameter_name }}</td>
                            <td class="py-4 border-bottom-0">
                                {{ $pathologyParameterItem->patient_result }}
                            </td>
                            <td class="py-4 border-bottom-0">
                                {{ $pathologyParameterItem->pathologyParameter->reference_range }}
                            </td>
                            <td class="py-4 border-bottom-0">
                                {{ $pathologyParameterItem->pathologyParameter->pathologyUnit->name }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    </div>
</body>
