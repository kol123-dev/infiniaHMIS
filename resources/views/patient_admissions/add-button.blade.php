<div>
    @if (Auth::user()->hasRole('Admin|Doctor|Case Manager|Receptionist'))
        <a href="{{ route('patient.admissions.excel') }}"
            class="btn btn-primary {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }}">
            <i class="fas fa-file-excel"></i>
        </a>
    @endif

    <a href="{{ route('patient-admissions.create') }}"
        class="btn btn-primary">{{ __('messages.patient_admission.new_patient_admission') }}</a>
</div>
