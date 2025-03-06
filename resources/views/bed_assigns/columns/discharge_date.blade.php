<div class="d-flex align-items-center mt-2">
    @if($row->discharge_date === null)
        {{__('messages.common.n/a')}}
    @else
        <div class="badge bg-light-info">
            <div>{{ \Carbon\Carbon::parse($row->discharge_date )->isoFormat('Do MMMM YYYY') }}</div>
        </div>
    @endif
</div>
