<div>
    <a href="{{ route('ambulance.excel') }}" 
        class="btn btn-primary {{ getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4' }}">
        <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('ambulances.create') }}" class="btn btn-primary">{{ __('messages.ambulance.new_ambulance') }}</a>
</div>
