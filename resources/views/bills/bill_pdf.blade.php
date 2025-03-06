<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/logo.jpg') }}" type="image/png">
    <title>{{ __('messages.bill.bill_details') }}</title>
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
    </style>
</head>

<body>
    <table width="100%" class="mb-20">
        <table width="100%">
            <tr>
                <td class="header-left">
                    <div class="main-heading">{{ __('messages.bill.bill') }}</div>
                    <div class="invoice-number font-color-gray">{{ __('messages.bill.admission_id') }}
                        #{{ $bill->patient_admission_id }}</div>
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
                                    <td class="" colspan="2">
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
                                        {{ $bill->patient->user->full_name }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.email') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill->patient->user->email }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.cell_no') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patient->user->phone) ? $bill->patient->user->phone : __('messages.common.n/a') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.gender') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill->patient->user->gender == 0 ? 'Male' : 'Female' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.user.dob') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patient->user->dob) ? Datetime::createFromFormat('Y-m-d', $bill->patient->user->dob)->format('jS M, Y g:i A') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.bill_id') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        #{{ $bill->bill_id }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.bill_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('jS M,Y g:i A') }}
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
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.investigation_report.doctor') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ $bill->patientAdmission->doctor->user->full_name }}
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.admission_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ Datetime::createFromFormat('Y-m-d H:i:s', $bill->patientAdmission->admission_date)->format('jS M, Y g:i A') }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.discharge_date') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patientAdmission->discharge_date) ? date('jS M, Y g:i A', strtotime($bill->patientAdmission->discharge_date)) : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.package.package') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patientAdmission->package->name) ? $bill->patientAdmission->package->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.insurance.insurance') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patientAdmission->insurance->name) ? $bill->patientAdmission->insurance->name : 'N/A' }}
                                    </td>
                                </tr>
                                <tr class="lh-2">
                                    <td class="">
                                        <label for="name"
                                            class="pb-2 fs-5 font-weight-bold me-1">{{ __('messages.bill.policy_no') }}:</label>
                                    </td>
                                    <td class="text-end fs-5 text-gray-800">
                                        {{ !empty($bill->patientAdmission->policy_no) ? $bill->patientAdmission->policy_no : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table w-100">
                <tr>
                    <td colspan="2">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.bill.item_name') }}</th>
                                    <th class="number-align">{{ __('messages.bill.qty') }}</th>
                                    <th class="number-align">{{ __('messages.bill.price') }}
                                        (<b>{{ getCurrencySymbol() }}</b>)
                                    </th>
                                    <th class="number-align">{{ __('messages.bill.amount') }}
                                        (<b>{{ getCurrencySymbol() }}</b>)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($bill) && !empty($bill))
                                    @foreach ($bill->billItems as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->item_name }}</td>
                                            <td class="number-align">{{ $item->qty }}</td>
                                            <td class="number-align">
                                                {{ checkNumberFormat($item->price, strtoupper(getCurrentCurrency())) }}
                                            </td>
                                            <td class="number-align">
                                                {{ checkNumberFormat($item->amount, strtoupper(getCurrentCurrency())) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table class="bill-footer">
                            <tr>
                                <td class="font-weight-bold">{{ __('messages.bill.total_amount') . ':' }}</td>
                                <td>  {{ checkNumberFormat($bill->amount, strtoupper(getCurrentCurrency())) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </table>
</body>

</html>
