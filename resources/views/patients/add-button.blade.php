<div>
    <a href="{{ route('patient.excel') }}"
        class="btn btn-primary {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }}" data-tirbo='false'>
        <i class="fas fa-file-excel"></i>
    </a>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">{{ __('messages.patient.new_patient') }}</a>
    </a>
</div>
