<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="email" value="1" {{ $row->email == 1 ? 'checked' : '' }}
        role="switch" id="emailStatus" data-id="{{ $row->id }}">
</div>
