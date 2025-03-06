<div class="form-check form-switch">
    <input class="form-check-input" name="address" value="1" id="address" {{ $row->address == 1 ? 'checked' : '' }} type="checkbox"
        role="switch" id="flexSwitchCheckDefault" data-id="{{ $row->id }}">
</div>
