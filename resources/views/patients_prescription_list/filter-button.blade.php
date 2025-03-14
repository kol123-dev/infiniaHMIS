<div class="ms-0 ms-md-2" wire:ignore>
    <div
        class="dropdown d-flex align-items-center {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }} me-md-5">
        <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false" id="patientsPrescriptionFilterBtn">
            <i class='fas fa-filter'></i>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="patientsPrescriptionFilterBtn">
            <div
                class="{{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <div class="mb-10">
                    <label class="form-label fs-6 fw-bold">{{ __('messages.common.status') . ':' }}</label>
                    {{ Form::select('status',collect($filterHeads[0])->sortBy('key')->toArray(),null,['id' => 'patients_prescription_filter_status', 'data-control' => 'select2', 'class' => 'form-select role-selector']) }}
                </div>
                <div class="d-flex justify-content-end">
                    <button type="reset" id="patientPrescriptionResetFilter"
                        class="btn btn-secondary">{{ __('messages.common.reset') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
