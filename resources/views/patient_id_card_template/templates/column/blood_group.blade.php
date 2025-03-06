<div class="form-check form-switch">
    <input class="form-check-input" name="blood_group" value="1" id="bloodGroup" {{ $row->blood_group == 1 ? 'checked' : '' }}
        type="checkbox" role="switch" id="flexSwitchCheckDefault" data-id="{{ $row->id }}">
</div>
