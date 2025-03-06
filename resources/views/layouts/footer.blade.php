<div class="footer py-4 d-flex flex-lg-column position-sticky bottom-0">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="text-muted">
            <span>{{__('messages.footer.all_rights_reserved')}}</span>
            <span class="text-muted fw-bold me-1">&copy {{ date('Y') }}</span>
            <a  href="{{ url('/') }}" class="text-hover-primary">{{ config('app.name') }}</a>
        </div>
        <div class="text-muted order-2 order-md-1">
            @if(env('VERSION_NUMBER'))
                v{{ getCurrentVersion() }}
            @endif
        </div>
    </div>
</div>
