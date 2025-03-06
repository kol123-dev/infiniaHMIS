<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "//www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/img/hms-saas-favicon.ico') }}" type="image/png">
    <title>{{ __('messages.patient_id_card.patient_id_card') }}</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="stylesheet" href="{{ asset('assets/css/patient-id-card_pdf.css') }}">
</head>

<body>
    @php
        $settingValue = getSettingValue();
    @endphp

    <div class="px-30" style="box-shadow:#64646f 0px 7px 29px 0px;width:650px;margin:auto;">
        <table class="w-100 smart-card-header"
            style="background-color: {{ !empty($patientIdCardTemplateData->color) ? $patientIdCardTemplateData->color : '' }};">
            <tbody>
                <tr>
                    <td>
                        <div class="logo me-4">
                            <img src="{{ asset($settingValue['app_logo']['value']) }}" alt="logo"
                                class="h-100 img-fluid" />
                        </div>
                    </td>
                    <td class="">
                        <h4 class="text-white mb-0 fw-bold pb">{{ getAppName() }}</h4>
                    </td>
                    <td class="text-white text-end pe-2" width="200%">
                        {{ $settingValue['hospital_address']['value'] }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="border-radius: 0 0 12px 12px; border-style: solid;border-color: lightgray;height:180px;" class="w-100">
            <tbody>
                <tr>
                    <td class="patient-card-body">
                        <div class="user-profile">
                            <a>
                                <div>
                                    <img src="data:image/png;base64, {{ $data['profile'] }}" alt="" height="110px" width="110px"
                                        class="user-img image" style="border-radius: 50%;">
                                </div>
                            </a>
                        </div>
                    </td>
                    <td width="100%">
                        <table class="table table-borderless patient-desc mb-0 my-3" style="margin-left: 5px;">
                            @if (!empty($patientIdCardData->patientUser->full_name))
                                <tr id="cardName" class="lh-1">
                                    <td>{{ __('messages.bill.patient_name') }}:</td>
                                    <td>{{ $patientIdCardData->patientUser->full_name }}</td>
                                </tr>
                            @endif
                            @if (!empty($patientIdCardData->patientUser->email) && $patientIdCardData->idCardTemplate->email)
                                <tr id="patientEmail" class="lh-1">
                                    <td>{{ __('auth.email') }}:</td>
                                    <td class="word-break">{{ $patientIdCardData->patientUser->email }}</td>
                                </tr>
                            @endif
                            @if (!empty($patientIdCardData->patientUser->phone) && $patientIdCardData->idCardTemplate->phone)
                                <tr id="patientNumber" class="lh-1">
                                    <td>{{ __('messages.sms.phone_number') }}:</td>
                                    <td>{{ $patientIdCardData->patientUser->phone }}</td>
                                </tr>
                            @endif
                            @if (!empty($patientIdCardData->patientUser->dob) && $patientIdCardData->idCardTemplate->dob)
                                <tr id="patientDob" class="lh-1">
                                    <td>{{ __('messages.user.dob') }}:</td>
                                    <td>{{ $patientIdCardData->patientUser->dob }}</td>
                                </tr>
                            @endif
                            @if (!empty($patientIdCardData->patientUser->blood_group) && $patientIdCardData->idCardTemplate->blood_group)
                                <tr id="patientBloodGroup" class="lh-1">
                                    <td>{{ __('messages.user.blood_group') }}:</td>
                                    <td>{{ $patientIdCardData->patientUser->blood_group }}</td>
                                </tr>
                            @endif
                            @if (!empty($patientIdCardData->address) && $patientIdCardData->idCardTemplate->address)
                                <tr id="patientBloodGroup" class="lh-1">
                                    <td>{{ __('messages.common.address') }}:</td>
                                    <td class="word-break">{{ $patientIdCardData->address->address1 }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                    <td>
                        <div class="mx-3 my-4">
                            <div class="text-center">
                                <img src="data:image/png;base64, {!! base64_encode($qrCode) !!} ">
                            </div>
                            @if (!empty($patientIdCardTemplateData->patient_unique_id))
                                <h5 class="text-primary text-center mt-3" id="patientUniqueID">
                                    {{ $patientIdCardData->patient_unique_id }}
                                </h5>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
