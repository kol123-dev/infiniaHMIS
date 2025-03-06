@if ($row->created_at === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div class="mb-2">{{ \Carbon\Carbon::parse($row->created_at)->format('h:i A') }}
            <div class="mt-2">
                {{ \Carbon\Carbon::parse($row->created_at)->isoFormat('Do MMMM YYYY') }}
            </div>
        </div>
@endif
