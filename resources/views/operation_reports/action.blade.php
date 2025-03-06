<div class="d-flex align-items-center">
    @if(!getLoggedInUser()->hasRole('Patient'))
        <a href="javascript:void(0)" title="{{__('messages.common.edit') }}" data-id="{{ $row->id }}"
        class="editOperationReportsBtn btn px-1 text-primary fs-3 ps-0">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
        class="deleteOperationReportsBtn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName() == 'ar' ? 'me-2' : ''}}" wire:key="{{$row->id}}">
            <i class="fa-solid fa-trash"></i>
        </a>
    @else
        <a href="{{ url('operation-reports'.'/'.$row->id) }}"  title="<?php echo __('messages.common.view') ?>"
            class="btn px-1 text-info fs-3">
            <i class="fas fa-eye"></i>
        </a>
     @endif
</div>
