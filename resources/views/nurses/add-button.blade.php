<div>
    <a href="{{ route('nurses.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('nurses.create') }}"
       class="btn btn-primary">{{ __('messages.nurse.new_nurse') }}</a>
</div>
