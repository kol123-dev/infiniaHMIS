<a href="{{ route('bed-assigns.edit',$row->id)}}" title="{{__('messages.common.edit') }}"
   class="bed-assign-edit-btn btn px-1 text-primary fs-3 ps-0">
    <i class="fa-solid fa-pen-to-square"></i>
</a>
<a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
   class="bed-assign-delete-btn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : ''}}" wire:key="{{$row->id}}">
    <i class="fa-solid fa-trash"></i>
</a>
