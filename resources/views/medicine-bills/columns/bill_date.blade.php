@if ($row->created_at === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-primary">
        <div class="mb-2">{{ \Carbon\Carbon::parse($row->created_at)->format('h:i A') }}
            <div class="mt-2">
                {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('jS M, Y') }}
            </div>
        </div>
@endif
