<div>
    <a href="{{ route('visitors.excel') }}" class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}" >
        <i class="fas fa-file-excel"></i>
    </a>

    <a href="{{ route('visitors.create') }}" class="btn btn-primary">{{ __('messages.visitor.new') }}</a>
</div>
