<div>
    <a href="{{ route('receptionists.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('receptionists.create') }}"
       class="btn btn-primary">{{ __('messages.receptionist.new_receptionist') }}</a>
</div>
