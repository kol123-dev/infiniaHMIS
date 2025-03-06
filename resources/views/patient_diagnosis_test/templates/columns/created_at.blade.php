<div class="d-flex align-items-center mt-2">
    <span class="badge bg-light-info">
        @if ($row->created_at === null)
            {{ __('messages.common.n/a') }}
        @endif
        {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('jS M,Y') }}
    </span>
</div>
