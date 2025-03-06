<script id="invoiceItemTemplate" type="text/x-jsrender">
    <tr class="border-bottom border-bottom-dashed">
    <td class="text-center pt-5 item-number">1</td>
    <td class="table__item-desc">
        <select class="form-select accountId" name="account_id[]" placeholder="<?php echo __('messages.common.choose') . ' ' . __('messages.payment.account') ?>" id="enquiry-account-id_{{:uniqueId}}" data-id="{{:uniqueId}}" required>
            <option selected="selected" value=0"><?php echo __('messages.common.choose') . ' ' . __('messages.payment.account') ?></option>
            {{for accounts}}
                <option value="{{:key}}">{{:value}}</option>
            {{/for}}
        </select>
    </td>
    <td class="text-center">
        <input class="form-control" name="description[]" type="text" placeholder="<?php echo __('messages.invoice.description') ?>">
    </td>
    <td class="table__qty text-center">
        <input class="form-control qty" required="" name="quantity[]" type="number" min="1" placeholder="<?php echo __('messages.invoice.qty') ?>">
    </td>
    <td class="text-center">
        <input class="form-control price-input price" required="" name="price[]" type="text" placeholder="<?php echo __('messages.invoice.price') ?>">
    </td>
    <td class="amount text-center item-total pt-5 text-nowrap">
    0.00
    </td>
    <td class="text-end">
    <a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>" data-id="{{$row->id}}" wire:key="{{$row->id}}"
   class="deleteInvoiceItem  btn px-1 text-danger fs-3 pe-0">
        <i class="fa-solid fa-trash"></i>
    </a>
    </td>
</tr>
</script>
