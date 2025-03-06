<div>
    <a href="{{ route('pharmacists.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('pharmacists.create') }}"
       class="btn btn-primary">{{ __('messages.pharmacist.new_pharmacist') }}</a>
</div>
