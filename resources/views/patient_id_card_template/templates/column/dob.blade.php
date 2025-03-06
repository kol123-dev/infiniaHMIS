<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="dob" id="dob" {{ $row->dob == 1 ? 'checked' : '' }}
        value="1" role="switch" id="flexSwitchCheckDefault" data-id="{{ $row->id }}">
</div>
