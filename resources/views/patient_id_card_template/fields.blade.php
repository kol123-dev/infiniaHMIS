<div class="card main-div d-flex flex-xxl-row flex-column-reverse">
    <div class="col-xl-12 p-5 col-xxl-4">
        <div class="row col-8">
            <div class="mb-5">
                {{ Form::label('Template name', __('messages.user.name'), ['class' => 'form-label required']) }}
                {{ Form::text('name', isset($patientIdCardTemplateData) ? $patientIdCardTemplateData->name : null, ['class' => 'form-control', 'id' => 'template_name', 'placeholder' => __('messages.user.name'), 'required']) }}
            </div>
            <div class="mb-5">
                {{ Form::label('Header Color', __('messages.patient_id_card.color'), ['class' => 'form-label required']) }}
                <br>
                {{ Form::color('color', isset($patientIdCardTemplateData) ? $patientIdCardTemplateData->color : null, ['class' => '', 'id' => 'CreateColor', 'placeholder' => '', 'required']) }}
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.user.email') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="email" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->email == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createEmailStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.user.phone') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="phone" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->phone == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createPhoneStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.user.dob') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="dob" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->dob == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createDobStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.user.blood_group') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="blood_group" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->blood_group == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createBloodGroupStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.common.address') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="address" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->address == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createAddressStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label class="form-label">{{ __('messages.patient_id_card.patient_unique_id') }}:</label>
                <div class="col-lg-8">
                    <div class="form-check form-check-solid form-switch">
                        <input tabindex="12" name="patient_unique_id" value="1"
                            {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->patient_unique_id == 1 ? 'checked' : '' }}
                            {{ !isset($patientIdCardTemplateData) ? 'checked' : '' }} class="form-check-input"
                            type="checkbox" id="createUniqueIdStatus">
                        <label class="form-check-label" for="allowmarketing"></label>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                {{ Form::submit(__('messages.common.save'), ['class' => (getCurrentLoginUserLanguageName() == 'ar' ? 'btn btn-primary ms-2' : 'btn btn-primary me-2')]) }}
                <a href="{{ route('smart-patient-cards.index') }}" type="reset"
                    class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="col-xl-12 p-5 col-xxl-8">
        @php
            $settingValue = getSettingValue();
            $styles = 'style';
        @endphp
        <div class="px-30 mx-md-auto" {{ $styles }}="border:solid black 1px;border-radius:12px;width:600px;">
            <table class="w-100">
                <tbody>
                    <div class="d-flex smart-card-header align-items-center"
                        {{ $styles }}="border-radius: 12px 12px 0 0;background-color: {{ !empty($patientIdCardTemplateData->color) ? $patientIdCardTemplateData->color : '' }}">
                        <div
                            class="flex-1 d-flex align-items-center {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-3' : 'me-3' }}">
                            <div class="logo {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }}">
                                <img src="{{ asset($settingValue['app_logo']['value']) }}" alt="logo"
                                    {{ $styles }}="height:40px" />
                            </div>
                            <h4 class="text-white mb-0 fw-bold">{{ getAppName() }}</h4>
                        </div>
                        <div class="flex-1 {{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-start' : 'text-end' }}">
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
                <tbody>
                    <tr class="d-sm-flex d-md-block flex-md-row flex-column">
                        <td class="patient-card-body" {{ $styles }}="width:20%;">
                            <div
                                class="user-profile pb-5 {{ getCurrentLoginUserLanguageName() == 'ar' ? 'me-3' : 'ms-3' }}">
                                <a>
                                    <div>
                                        <img src="{{ asset('/assets/img/avatar.png') }}" alt=""
                                            id="card_profilePicture" width="110px"
                                            class="user-img rounded-circle image">
                                    </div>
                                </a>
                            </div>
                        </td>
                        <td>
                            <table
                                class="table table-borderless patient-desc my-3 {{ getCurrentLoginUserLanguageName() == 'ar' ? 'me-2' : 'ms-2' }}">
                                <tr>
                                    <td class="text-dark" {{ $styles }}="width:80px;">
                                        {{ __('messages.bill.patient_name') }}:</td>
                                    <td class="text-dark">James Bond</td>
                                </tr>
                                <tr id="ShowCreateEmail"
                                    class="lh-1 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->email == 0 ? 'd-none' : '' }}">
                                    <td class="text-dark">{{ __('messages.user.email') }}:</td>
                                    <td class="text-dark" {{ $styles }}="word-break: break-all;width:120px;">
                                        JamesBond@gmail.com</td>
                                </tr>
                                <tr id="ShowCreatePhone"
                                    class="lh-1 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->phone == 0 ? 'd-none' : '' }}">
                                    <td class="text-dark">{{ __('messages.user.phone') }}:</td>
                                    <td class="text-dark">1234567890</td>
                                </tr>
                                <tr id="ShowCreateDob"
                                    class="lh-1 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->dob == 0 ? 'd-none' : '' }}">
                                    <td class="text-dark">{{ __('messages.user.dob') }}:</td>
                                    <td class="text-dark">25/02/2006</td>
                                </tr>
                                <tr id="ShowCreateBloodGroup"
                                    class="lh-1 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->blood_group == 0 ? 'd-none' : '' }}">
                                    <td class="text-dark">{{ __('messages.user.blood_group') }}:</td>
                                    <td class="text-dark">A+</td>
                                </tr>
                                <tr id="ShowCreateAddress"
                                    class="lh-1 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->address == 0 ? 'd-none' : '' }}">
                                    <td class="text-dark">{{ __('messages.common.address') }}:</td>
                                    <td class="card_address text-dark">D.No.1 Street name Address</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <div class="text-center mt-3">
                                <div>
                                    {{ QrCode::generate('Make me into a QrCode!') }}
                                </div>
                            </div>
                            <h5 class="text-center mt-3 {{ isset($patientIdCardTemplateData) && $patientIdCardTemplateData->patient_unique_id == 0 ? 'd-none' : '' }}"
                                id="ShowUniqueId">
                                ABCDEFG
                            </h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
