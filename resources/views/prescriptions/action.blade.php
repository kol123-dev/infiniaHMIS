<div class="d-flex align-items-center">
    <a href="{{url('prescriptions'.'/'.$row->id.'/view')}}"  title="<?php echo __('messages.common.view') ?>"
        class="btn px-1 text-info fs-3">
         <i class="fas fa-eye"></i>
     </a>
     @php
         $medicineBill = App\Models\MedicineBill::whereModelType('App\Models\Prescription')->whereModelId($row->id)->first();
     @endphp
     @if(isset($medicineBill->payment_status) && $medicineBill->payment_status == false)
         <a href="{{url('prescriptions'.'/'.$row->id.'/edit')}}" title="<?php echo __('messages.common.edit') ?>"
         class="btn px-1 text-primary fs-3 ps-0  edit-prescription-btn">
             <i class="fa-solid fa-pen-to-square"></i>
         </a>
     @endif
     <a href="{{ route('prescriptions.pdf',$row->id) }}" title="<?php echo __('messages.ipd_patient_prescription.print_prescription') ?>"
         class="btn px-1 text-warning fs-3 ps-0 " target="_blank">
         <i class="fa fa-print" aria-hidden="true"></i>
      </a>
     <a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}" wire:key="{{$row->id}}"
        class="delete-prescription-btn btn px-1 text-danger fs-3 ps-0 ">
         <i class="fa-solid fa-trash"></i>
     </a>
</div>
