@if ($row->date === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div>{{ \Carbon\Carbon::parse($row->date)->isoFormat('Do MMMM YYYY') }}

        </div>
    </div>
@endif
