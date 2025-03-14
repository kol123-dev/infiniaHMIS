<div class="d-flex {{getCurrentLoginUserLanguageName() == 'ar' ? 'justify-content-center' : 'justify-content-end'}} w-75 ps-125 text-center">
    <a title="{{__('messages.common.edit')}}" data-id="{{ $row->id }}"
       class="btn px-1 text-primary fs-3 ps-0 doctor-department-edit-btn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a title="{{__('messages.common.delete')}}" href="javascript:void(0)" data-id="{{ $row->id }}" wire:key="{{$row->id}}"
       class="btn px-1 text-danger fs-3 pe-0 doctor-department-delete-btn {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : ''}}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
