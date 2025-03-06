@if ($row->dose_given_date === null)
    {{ __('messages.common.n/a') }}
@endif
<div class="badge bg-light-info">
    <div class="mb-2">{{ \Carbon\Carbon::parse($row->dose_given_date)->format('h:i A') }}
    </div>
    <div>
        {{ \Carbon\Carbon::parse($row->dose_given_date)->translatedFormat('jS M, Y') }}
    </div>
</div>
