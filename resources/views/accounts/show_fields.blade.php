<div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="poverview" role="tabpanel">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.account.account') . ':' }}</label>
                            <span class="fs-5 text-gray-800">{{ $account->name }}</span>
                        </div>
                        <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.account.type') . ':' }}</label>
                            <p class="m-0">
                                <span
                                    class="badge bg-light-{{ $account->type == 1 ? 'danger' : 'success' }}">{{ $account->type == 1 ? __('messages.account.debit') : __('messages.account.credit') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.common.status') . ':' }}</label>
                            <p class="m-0">
                                <span
                                    class="badge bg-light-{{ $account->status == 1 ? 'success' : 'danger' }}">{{ $account->status == 1 ? __('messages.common.active') : __('messages.common.de_active') }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 d-flex flex-column mb-md-10 mb-5">
                            <label class="pb-2 fs-5 text-gray-600">{{ __('messages.account.description') }}</label>
                            <span class="fs-5 text-gray-800">{!! $account->description != '' ? nl2br(e($account->description)) : __('messages.common.n/a') !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="d-md-flex align-items-center justify-content-between">
            <h1 class="fs-5 m-0">{{ __('messages.payment.payments') }}</h1>
        </div>
    </div>
    <livewire:payment-table-account accountId="{{ $account->id }}" />
</div>
