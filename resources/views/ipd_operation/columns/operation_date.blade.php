@if ($row->operation_date === null)
    {{ __('messages.common.n/a') }}
@else
    <div class="badge bg-light-info">
        <div>{{\Carbon\Carbon::parse($row->operation_date)->translatedFormat('jS M,Y')}}</div>
    </div>
@endif
