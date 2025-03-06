<div class="d-flex align-items-center">
    <a href="javascript:void(0)" class="btn px-1 text-primary fs-3 ps-0 updateOperationBtn" title="{{__('messages.common.edit') }}" data-bs-toggle="modal" data-bs-target="#edit_operations_modal" data-id="{{ $row->id }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="deleteOperationBtn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : ''}}" wire:key="{{$row->id}}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
