@if ($row->date === null)
    {{ __('messages.common.n/a') }}
@endif

<div class="badge bg-light-info">
    <div class="mb-2">{{ \Carbon\Carbon::parse($row->date)->isoFormat('LT') }}</div>
    <div>{{ \Carbon\Carbon::parse($row->date)->isoFormat('Do MMMM YYYY') }}</div>
</div>
