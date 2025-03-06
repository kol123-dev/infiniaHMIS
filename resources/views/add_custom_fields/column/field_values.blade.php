<div class="d-flex align-items-center">
    @if (!empty($row->values))
        {{ $row->values }}
    @else
        {{ 'N/A' }}
    @endif
</div>
