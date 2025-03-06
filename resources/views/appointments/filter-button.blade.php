<div class="d-flex align-items-center py-1">
    <a href="{{ route('appointment-calendars.index') }}"
        class="btn btn-icon btn-primary {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-2' : 'me-2' }}">
        <i class="fas fa-calendar-alt fs-3"></i>
    </a>
    <div class="ms-0 ms-md-2" wire:ignore>
        <div
            class="dropdown d-flex align-items-center {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-2' : 'me-4' }} me-md-5">
            <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
                id="appointmentFilterBtn" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
                <i class='fas fa-filter'></i>
            </button>
            <div class="dropdown-menu py-0" aria-labelledby="appointmentFilterBtn">
                <div
                    class="{{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                    <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <label for="exampleInputSelect2"
                            class="form-label">{{ __('messages.common.status') . ':' }}</label>
                        {{-- {{ Form::select('status', $filterHeads[0], null, ['id' => 'appointmentStatus', 'data-control' => 'select2', 'class' => 'form-select status-selector select2-hidden-accessible data-allow-clear="true"']) }} --}}
                        <select class="form-select status-selector " id="appointmentStatus" data-control="select2"
                            name="status">
                            <option value="2">{{ __('messages.filter.All') }}</option>
                            <option value="0">{{ __('messages.appointment.pending') }}</option>
                            <option value="1">{{ __('messages.appointment.completed') }}</option>
                            <option value="3">{{ __('messages.live_consultation.cancelled') }}</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="reset" id="appointmentResetFilter"
                            class="btn btn-secondary">{{ __('messages.common.reset') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
