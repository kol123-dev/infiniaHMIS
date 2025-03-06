<div class="row mt-4">
    @foreach ($addOnModules as $addOnModule)
        @if (isModuleInstalled($addOnModule->name))
            <div class="col-md-3 mb-4">
                <div class="card text-center border-primary">
                    <div class="d-flex justify-content-end pe-4">
                        <a href="javascript:void(0)" data-id="{{ $addOnModule->id }}" data-title="{{ $addOnModule->name }}"
                            class="deleteAddonModule btn px-1 text-danger fs-3 pe-0 {{ getCurrentLoginUserLanguageName() === 'ar' ? 'me-2' : '' }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                    <div class="card-body pt-1">
                        <h5 class="card-title">{{ $addOnModule->name }}</h5>

                        <button type="button"
                            class="btn {{ $addOnModule->status == 1 ? 'btn-danger' : 'btn-primary' }} btn-sm mt-4 disableModule"
                            data-id="{{ $addOnModule->id }}">
                            {{ $addOnModule->status == 1 ? __('messages.addon.disable') : __('messages.addon.enable') }}
                        </button>

                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
