@php
    $medicineBill = App\Models\MedicineBill::whereModelType('App\Models\OpdPrescription')->whereModelId($row->id)->first();
@endphp

<a href="javascript:void(0)"
    class="viewOpdPrescription btn px-1 text-info fs-3" data-id="{{$row->id}}">
     <i class="fas fa-eye"></i>
 </a>

@if (!getLoggedinPatient())
    @if(isset($medicineBill->payment_status) && $medicineBill->payment_status == false)
    <a title="<?php echo __('messages.common.edit') ?>" data-id="{{$row->id}}"
    class="btn px-1 text-primary fs-3 ps-0 editOpdPrescriptionBtn">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endif

    <a href="{{ route('opdPrescription.pdf', $row->id) }}"
    class="btn px-1 text-warning fs-3 ps-0" target="_blank">
    <i class="fa fa-print" aria-hidden="true"></i>
    </a>

    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}"
    class="deleteOpdPrescriptionBtn btn px-1 text-danger fs-3 ps-0" wire:key="{{$row->id}}">
    <i class="fa-solid fa-trash"></i>
    </a>
@endif
