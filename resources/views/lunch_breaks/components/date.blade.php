<span class="badge bg-light-info me-2">
    @if (! empty($row->date))
        {{ \Carbon\Carbon::parse($row->date)->isoFormat('DD MMM YYYY') }}
    @else
        {{ __('messages.lunch_break.every_day') }}
    @endif
</span>
