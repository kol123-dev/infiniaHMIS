<div class="ms-0 ms-md-2" wire:ignore>
    <div
        class="dropdown d-flex align-items-center {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }} me-md-5">
        <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false" id="accountantFilterBtn">
            <i class='fas fa-filter'></i>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="accountantFilterBtn">
            <div
                class="{{ getCurrentLoginUserLanguageName() == 'ar' ? 'text-end' : 'text-start' }} border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <div class="mb-10">
                    <label class="form-label fs-6 fw-bold">{{ __('messages.common.status') . ':' }}</label>
                    {{ Form::select('account_status', localization('filter', $component->FilterComponent[1]), null, ['id' => 'account_filter_status', 'data-control' => 'select2', 'class' => 'form-select role-selector']) }}
                </div>
                <div class="mb-10">
                    <label class="form-label fs-6 fw-bold">{{ __('messages.account.type') . ':' }}</label>
                    <br>
                    {{-- {{ Form::select('account_type',$component->FilterComponent[2],null,['id'=>'account_filter_type','data-control' =>'select2', 'class'=>'form-select role-selector']) }} --}}
                    <select class="form-select status-selector " id="account_filter_type" data-control="select2"
                        name="account_type">
                        <option value="0">{{ __('messages.filter.All') }}</option>
                        <option value="2">{{ __('messages.account.credit') }}</option>
                        <option value="1">{{ __('messages.account.debit') }}</option>
                    </select>

                </div>
                <div class="d-flex justify-content-end">
                    <button type="reset" id="accountResetFilter"
                        class="btn btn-secondary">{{ __('messages.common.reset') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
