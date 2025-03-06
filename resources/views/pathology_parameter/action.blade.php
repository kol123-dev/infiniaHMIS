<div class="d-flex justify-content-center align-items-center">
    <a title="<?php echo __('messages.common.edit') ?>" data-id="{{$row->id}}"
       class="btn px-2 text-primary fs-3 edit-pathology-parameter-btn">
        <i class="fa fa-edit action-icon"></i>
    </a>
    <a href="#" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}"
       class="delete-pathology-parameter-btn btn px-2 text-danger fs-3" wire:key="{{$row->id}}" >
        <i class="fa fa-trash action-icon"></i>
    </a>
</div>
