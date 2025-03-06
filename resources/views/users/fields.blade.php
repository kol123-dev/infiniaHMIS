<div class="alert alert-danger d-none hide" id="userValidationErrorsBox"></div>
<div class="row">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('first_name', __('messages.user.first_name') . ':', ['class' => 'form-label required']) }}
            {{ Form::text('first_name', null, ['class' => 'form-control', 'required', 'tabindex' => '1', 'placeholder' => __('messages.user.first_name')]) }}
        </div>
    </div>
    <div class="col-lg-6 mb-5">
        {{ Form::label('last_name', __('messages.user.last_name') . ':', ['class' => 'form-label required ']) }}
        {{ Form::text('last_name', null, ['class' => 'form-control', 'required', 'tabindex' => '2', 'placeholder' => __('messages.user.last_name')]) }}
    </div>
    <div class="col-lg-6 mb-5">
        {{ Form::label('email', __('messages.user.email') . ':', ['class' => 'form-label required']) }}
        {{ Form::email('email', null, ['class' => 'form-control', 'required', 'tabindex' => '3', 'id' => 'userEmail', 'placeholder' => __('messages.user.email'), 'autocomplete' => 'off']) }}
    </div>
    @if (!$isEdit)
        <div class="col-lg-6 mb-5">
            {{ Form::label('department_id', __('messages.employee_payroll.role') . ':', ['class' => 'form-label']) }}
            <span class="text-danger">*</span>
            {{-- {{ Form::select('department_id', dump($role), null, ['class' => 'form-select fw-bold', 'required', 'id' => 'userRole', 'placeholder' => __('messages.role.select_role'), 'data-control' => 'select2']) }} --}}
            <select class="form-select status-selector " id="userRole" data-control="select2" name="department_id">
                <option value="">{{ __('messages.role.select_role') }}</option>
                <option value="1">{{ __('messages.admin') }}</option>
                <option value="2">{{ __('messages.doctors') }}</option>
                <option value="3">{{ __('messages.patients') }}</option>
                <option value="4">{{ __('messages.nurses') }}</option>
                <option value="5">{{ __('messages.receptionists') }}</option>
                <option value="6">{{ __('messages.pharmacists') }}</option>
                <option value="7">{{ __('messages.accountants') }}</option>
                <option value="8">{{ __('messages.case_manager') }}</option>
                <option value="9">{{ __('messages.lab_technicians') }}</option>
            </select>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group mb-5">
            {{ Form::label('phone', __('messages.visitor.phone') . ':', ['class' => 'form-label']) }}
            <span class="required"></span>
            <br>
            {{ Form::tel('phone', $user->phone ?? getCountryCode(), ['class' => 'form-control phoneNumber', 'id' => 'userPhoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'tabindex' => '5', 'placeholder' => __('messages.user.phone')]) }}
            {{ Form::hidden('prefix_code', null, ['class' => 'prefix_code']) }}
            {{ Form::hidden('country_iso', null, ['class' => 'country_iso']) }}
            <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp; {{ __('messages.valid') }}</span>
            <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
        </div>
    </div>
    <div class="col-lg-6 mb-5">
        {{ Form::label('dob', __('messages.user.dob') . ':', ['class' => 'form-label']) }}
        {{ Form::text('dob', null, ['class' => getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control', 'id' => 'userDob', 'autocomplete' => 'off', 'tabindex' => '10', 'placeholder' => __('messages.user.dob')]) }}
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('gender', __('messages.user.gender') . ':', ['class' => 'form-label']) }}
            <span class="required"></span> &nbsp;<br>
            <div class="d-flex align-items-center">
                <div class="form-check {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-10' : 'me-10' }}">
                    <label class="form-label" for="accountantGenderMale">{{ __('messages.user.male') }}</label>
                    {{ Form::radio('gender', '0', true, ['class' => 'form-check-input', 'tabindex' => '6', 'id' => 'usesMale']) }}
                </div>
                <div class="form-check {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-10' : 'me-10' }}">
                    <label class="form-label">{{ __('messages.user.female') }}</label>&nbsp;
                    {{ Form::radio('gender', '1', false, ['class' => 'form-check-input', 'tabindex' => '7', 'id' => 'usesFemale']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-5">
            {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label']) }}
            <br>
            <div class="form-check form-switch">
                <input class="form-check-input is-active" name="status" type="checkbox" value="1" tabindex="11"
                    id="userAllowMarketing"
                    @if ($isEdit) {{ isset($user) && $user->status ? 'checked' : '' }} @else
                    {{ 'checked' }} @endif ">
                <label class="form-check-label" for="allowmarketing"></label>
            </div>
        </div>
    </div>
            @if (!$isEdit)
                <div class="col-lg-6 mb-5">
                    {{ Form::label('password', __('messages.user.password') . ':', ['class' => 'form-label required']) }}
                    {{ Form::password('password', ['class' => 'form-control', 'required', 'min' => '6', 'max' => '10', 'tabindex' => '8', 'placeholder' => __('messages.user.password'), 'autocomplete' => 'off']) }}
                </div>
                @endif
                @if (!$isEdit)
                    <div class="col-lg-6 mb-5">
                        {{ Form::label('password_confirmation', __('messages.user.password_confirmation') . ':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'required', 'min' => '6', 'max' => '10', 'tabindex' => '9', 'placeholder' => __('messages.user.password_confirmation')]) }}
                    </div>
                @endif
                <!-- Facebook URL Field -->
                <div class="col-lg-6 mb-5">
                    {{ Form::label('facebook_url', __('messages.facebook_url') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('facebook_url', null, ['class' => 'form-control', 'id' => 'userFacebookUrl', 'onkeypress' => 'return avoidSpace(event);', 'placeholder' => __('messages.facebook_url')]) }}
                </div>

                <!-- Instagram URL Field -->
                <div class="col-lg-6 mb-5">
                    {{ Form::label('instagram_url', __('messages.instagram_url') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('instagram_url', null, ['class' => 'form-control', 'id' => 'userInstagramUrl', 'onkeypress' => 'return avoidSpace(event);', 'placeholder' => __('messages.instagram_url')]) }}
                </div>
                <!-- Twitter URL Field -->
                <div class="col-lg-6 mb-5">
                    {{ Form::label('twitter_url', __('messages.twitter_url') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('twitter_url', null, ['class' => 'form-control', 'id' => 'userTwitterUrl', 'onkeypress' => 'return avoidSpace(event);', 'placeholder' => __('messages.twitter_url')]) }}
                </div>
                <!-- LinkedIn URL Field -->
                <div class="col-lg-6 mb-5">
                    {{ Form::label('linkedIn_url', __('messages.linkedIn_url') . ':', ['class' => 'form-label']) }}
                    {{ Form::text('linkedIn_url', null, ['class' => 'form-control', 'id' => 'userLinkedInUrl', 'onkeypress' => 'return avoidSpace(event);', 'placeholder' => __('messages.linkedIn_url')]) }}
                </div>
                <div class="form-group col-md-4 mb-5">
                    <div class="row2" io-image-input="true">
                        {{ Form::label('image', __('messages.common.profile') . ':', ['class' => 'form-label']) }}


                        <div class="d-block">
                            @php
                                if ($isEdit) {
                                    $image = isset($user->media[0]) ? $user->image_url : asset('assets/img/avatar.png');
                                } else {
                                    $image = asset('assets/img/avatar.png');
                                }
                            @endphp

                            <div class="image-picker">
                                <div class="image previewImage" id="userPreviewImage"
                                    style="background-image: url({{ $image }})">
                                    <span class="picker-edit rounded-circle text-gray-500 fs-small"
                                        title="{{ $isEdit ? __('messages.common.change_profile') : __('messages.common.profile') }}">

                                        <label>
                                            <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                            <input type="file" id="userProfileImage" name="image"
                                                class="image-upload d-none profileImage" ,
                                                accept=".png, .jpg, .jpeg, .gif" />
                                            <input type="hidden" name="avatar_remove" />
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    {{ Form::submit(__('messages.common.save'), ['class' => getCurrentLoginUserLanguageName() == 'ar' ? 'btn btn-primary btn-save ms-3' : 'btn btn-primary btn-save me-3']) }}
                    <a href="{!! route('users.index') !!}" class="btn btn-secondary">{{ __('messages.common.cancel') }}</a>
                </div>
            </div>
