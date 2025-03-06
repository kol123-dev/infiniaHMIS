<div>
    <a href="{{ route('lab.technicians.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('lab-technicians.create') }}"
       class="btn btn-primary">{{ __('messages.lab_tech.new_lab_tech') }}</a>
</div>
