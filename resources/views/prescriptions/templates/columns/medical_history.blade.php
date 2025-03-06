@if ($row->medical_history && strtotime($row->medical_history) !== false)
    <div class="badge bg-light-primary">
        {{ \Carbon\Carbon::parse($row->medical_history)->translatedFormat('jS M, Y') }}
    </div>
@else
    {{ __('messages.common.n/a') }}
@endif
