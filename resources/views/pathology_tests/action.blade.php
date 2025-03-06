<div class=" align-items-center">
    <a href="{{ route('pathology.test.pdf', $row->id) }}" title="<?php echo __('messages.new_change.print_pathology_test'); ?>" class="btn px-2 text-warning fs-3" target="_blank">
        <i class="fa fa-print"></i>
    </a>
    <a href="{{ route('pathology.test.edit',$row->id)}}" title="{{__('messages.common.edit') }}"
       class="btn px-1 text-primary fs-3 ps-0">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
       class="deletePathologyTestBtn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName() == 'ar' ? 'me-3' : ''}} " wire:key="{{$row->id}}">
        <i class="fa-solid fa-trash"></i>
    </a>
</div>
