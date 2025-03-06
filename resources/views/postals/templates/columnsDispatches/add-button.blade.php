<div>
    <a href="{{ route('dispatches.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>
            <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#add_postal_dispatch_modal" class="btn btn-primary">
                {{ __('messages.postal.new_dispatch') }}
            </a>
{{--            <a href="{{ route('dispatches.excel') }}"  class="dropdown-item  px-5">--}}

</div>
