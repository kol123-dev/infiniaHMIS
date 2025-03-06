<div>
    <a href="{{ route('case.handler.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-3' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>

    <a href="{{ route('case-handlers.create') }}"
       class="btn btn-primary">{{ __('messages.case_handler.new_case_handler') }}</a>
</div>
