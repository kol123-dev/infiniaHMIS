<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>{{ __('messages.medicine_bills.medicine_bill') }}</title>
    <link href="{{ asset('assets/css/bill-pdf.css') }}" rel="stylesheet" type="text/css" />
    @if (getCurrentCurrency() == 'inr')
        <style>
            body {
                font-family: DejaVu Sans, sans-serif !important;
            }
        </style>
    @endif
    <style>
        body {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

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
        .bill-footer{
            padding: 20px;
            margin-left: 101%;
        }
        .text-start {
            text-align: left !important;
        }

    </style>
</head>

<body>
    <table width="100%" class="mb-20">
        <table width="100%">
            <tr>
                <td class="header-left">
                    <div class="main-heading">{{ __('messages.medicine_bills.medicine_bill') }}</div>
                </td>
                <td class="header-right">
                    <div class="logo"><img width="100px" src="{{ $data['app_logo'] }}" alt=""></div>
                    <div class="hospital-name">{{ $data['app_name'] }}</div>
                    <div class="hospital-name font-color-gray">{{ $data['hospital_address'] }}</div>
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
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.patient') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $medicineBill->patient->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $medicineBill->patient->user->email }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.cell_no') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($medicineBill->patient->user->phone) ? $medicineBill->patient->user->phone : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $medicineBill->patient->user->gender == 0 ? __('messages.user.male') : __('messages.user.female') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.dob') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($medicineBill->patient->user->dob) ? Datetime::createFromFormat('Y-m-d', $medicineBill->patient->user->dob)->format('jS M, Y g:i A') : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                                @if (!empty($medicineBill->doctor))
                                    <tr class="lh-2">
                                        <td class="">
                                            <label for="name"
                                                class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.doctor') }}:</label>
                                        </td>
                                        <td class="text-end fs-5 text-gray-800">
                                            {{ $medicineBill->doctor->user->full_name }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                        <td style="width:2%;">
                        </td>
                        <td class="text-end desc ms-auto vertical-align-top bg-light">
                            <table class="table w-100">
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.bill_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        #{{ $medicineBill->bill_number }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.bill_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ \Carbon\Carbon::parse($medicineBill->bill_date)->format('jS M,Y g:i A') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.bill.item_name') }}</th>
                                    <th class="number-align">{{ __('messages.bill.qty') }}</th>
                                    <th class="number-align">{{ __('messages.bill.price') }}</th>
                                    <th class="number-align">{{ __('messages.purchase_medicine.tax') }}</th>
                                    <th class="number-align">{{ __('messages.bill.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($medicineBill->saleMedicine) && !empty($medicineBill->saleMedicine))
                                    @foreach ($medicineBill->saleMedicine as $index => $saleMedicine)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $saleMedicine->medicine->name }}
                                            </td>
                                            <td class="number-align">{{ $saleMedicine->sale_quantity }}</td>
                                            <td class="number-align">
                                                {{ getCurrencyFormat($saleMedicine->sale_price) }}</td>
                                            <td class="number-align">
                                                {{ $saleMedicine->tax . '%' }}</td>
                                            <td class="number-align">
                                                {{ getCurrencyFormat($saleMedicine->sale_price * $saleMedicine->sale_quantity) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%">

                <tr>
                    <td width="50%"></td>
                    <td>
                        <table class="bill-footer bg-light">
                            <tr>
                                <td class="font-weight-bold text-start">{{ __('messages.purchase_medicine.total') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    {{ getCurrencyFormat($medicineBill->total) }} </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-start">{{ __('messages.purchase_medicine.tax') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    {{ number_format($medicineBill->tax_amount, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-start">{{ __('messages.purchase_medicine.discount') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    {{ number_format($medicineBill->discount, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-start">{{ __('messages.purchase_medicine.net_amount') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    {{ getCurrencyFormat($medicineBill->net_amount) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </table>

</body>
