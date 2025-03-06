<div id="ShowPatientCardDataModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    @php
        $settingValue = getSettingValue();
        $styles = 'style';
    @endphp
    <div class="modal-dialog modal-lg d-flex justify-content-center">
        <div class="modal-content w-75" {{ $styles }}="width: 80% !important;">
            <div class="px-30">
                <table class="w-100">
                    <tbody>
                        <div class="d-flex justify-content-between smart-card-header align-items-center"
                            {{ $styles }}="padding:15px;border-radius: 9px 9px 0 0;">
                            <div class="flex-1 d-flex align-items-center {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-3' : 'me-3' }}">
                                <div class="logo {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }}">
                                    <img src="{{ asset($settingValue['app_logo']['value']) }}" alt="logo"
                                        {{ $styles }}="height:40px" />
                                </div>
                                <h4 class="text-white mb-0 fw-bold">{{ getAppName() }}</h4>
                            </div>
                            <div class="d-flex {{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-start' : 'text-end' }}">
                                <address class="text-white fs-12 mb-0">
                                    <p class="mb-0">
                                        {{ $settingValue['hospital_address']['value'] }}
                                    </p>
                                </address>
                            </div>
                        </div>
                    </tbody>
                </table>
                <table class="w-100">
                    <tbody {{ $styles }}="height: 180px;">
                        <tr>
                            <td class="patient-card-body">
                                <div class="user-profile pb-5 {{ getCurrentLoginUserLanguageName() == 'ar' ? 'me-3' : 'ms-3' }}">
                                    <a>
                                        <div>
                                            <img src="" alt="" id="card_profilePicture" width="110px"
                                                height="110px" class="user-img rounded-circle image"
                                                style="border-radius: 50%;">
                                        </div>
                                    </a>
                                </div>
                            </td>
                            <td width="100%">
                                <table class="table table-borderless patient-desc my-4 mx-2"
                                    {{ $styles }}="margin-left: 20px;">
                                    <tr id="cardName" class="lh-1">
                                        <td class="text-dark" {{ $styles }}="width:10px;">
                                            {{ __('messages.bill.patient_name') }}:</td>
                                        <td class="card_name text-dark"></td>
                                    </tr>
                                    <tr id="ShowCardEmail" class="lh-1">
                                        <td class="text-dark">{{ __('messages.user.email') }}:</td>
                                        <td class="patient_email text-dark"
                                            {{ $styles }}="width:60px;word-break: break-all;"></td>
                                    </tr>
                                    <tr id="ShowCardPhone" class="lh-1">
                                        <td class="text-dark">{{ __('messages.user.phone') }}:</td>
                                        <td class="patient_contact text-dark"></td>
                                    </tr>
                                    <tr id="ShowCardDob" class="lh-1">
                                        <td class="text-dark">{{ __('messages.user.dob') }}:</td>
                                        <td class="patient_dob text-dark"></td>
                                    </tr>
                                    <tr id="ShowCardBloodGroup" class="lh-1">
                                        <td class="text-dark">{{ __('messages.user.blood_group') }}:</td>
                                        <td class="blood_group text-dark"></td>
                                    </tr>
                                    <tr id="ShowCardAddress" class="lh-1">
                                        <td class="text-dark">{{ __('messages.common.address') }}:</td>
                                        <td class="card_address text-dark"></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <div class="mx-3">
                                    <div class="text-center mt-3">
                                        <div>
                                            <div class="svgContainer"></div>
                                        </div>
                                    </div>
                                    <h5 class="text-center mt-3 patient_unique_id" id="ShowPatientUniqueId">
                                    </h5>
                                    @role('Patient')
                                        <div class="text-center mt-5">
                                            <a href="{{ route('patient.id.card.pdf', Auth::user()->patient->id) }}"
                                                target="_blank" class="btn px-1 pe-3 fs-1 download-icon"
                                                data-bs-toggle="tooltip" data-bs-original-title="Download">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
