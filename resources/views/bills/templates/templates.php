<script id="billItemTemplate" type="text/x-jsrender">
    <tr>
    <td class="text-center item-number">1</td>
    <td class="table__item-desc">
        <input class="form-control itemName" required="" name="item_name[]" type="text" placeholder="<?php echo __('messages.bill.item_name')?>">
    </td>
    <td class="table__qty">
        <input class="form-control qty quantity" required="" name="qty[]" type="number" placeholder="<?php echo __('messages.bill.qty')?>">
    </td>
    <td>
        <input class="form-control price-input price" required="" name="price[]" type="text" placeholder="<?php echo __('messages.bill.price')?>">
    </td>
    <td class="amount text-right itemTotal">
        0.00
    </td>
    <td class="text-center">
        <i class="fa fa-trash text-danger delete-bill-bulk-item pointer"></i>
    </td>
</tr>


</script>
