<div>
    <a href="{{ route('call_logs.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('call_logs.create') }}"
    class="btn btn-primary  px-5">{{ __('messages.call_log.new') }}
    </a>
</div>
