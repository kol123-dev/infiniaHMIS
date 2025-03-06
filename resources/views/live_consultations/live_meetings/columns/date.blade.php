@if ($row->consultation_date === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div class="mb-2">{{ \Carbon\Carbon::parse($row->consultation_date)->format('h:i A') }}
        </div>
        <div>
            {{ \Carbon\Carbon::parse($row->consultation_date)->translatedFormat('jS M,Y') }}
        </div>
    </div>
@endif
