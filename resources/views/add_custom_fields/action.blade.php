<div class="d-flex justify-content-center">
    <a href="javascript:void(0)" class="btn px-1 text-primary fs-3 ps-0 updateCustomFieldBtn" title="{{__('messages.common.edit') }}" data-bs-toggle="modal" id="editCustomFieldBtn" data-bs-target="#edit_custom_field_modal" data-id="{{ $row->id }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a title="{{__('messages.common.delete')}}" href="javascript:void(0)"  data-id="{{ $row->id }}" wire:key="{{$row->id}}"
       class="btn px-1 text-danger fs-3 ps-0 custom-field-delete-btn {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : ''}}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
