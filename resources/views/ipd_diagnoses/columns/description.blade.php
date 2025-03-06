<div class="ipd-desc">
    @if ($row->description != '')
        <span>{{$row->description}}</span>
    @else
        {{__('messages.common.n/a')}}
    @endif
</div>
