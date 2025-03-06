<script id="billItemTemplate" type="text/x-jsrender">
    <tr>
    <td class="text-center item-number">1</td>
    <td class="table__item-desc">
        <input class="form-control itemName" required="" name="item_name[]" type="text">
    </td>
    <td class="table__qty">
        <input class="form-control qty quantity" required="" name="qty[]" type="number">
    </td>
    <td>
        <input class="form-control price-input price" required="" name="price[]" type="text">
    </td>
    <td class="amount text-right itemTotal">
    </td>
    <td class="text-center">
        <i class="fa fa-trash text-danger delete-bill-bulk-item pointer"></i>
    </td>
</tr>


</script>

<script id="medicineBillTemplate" type="text/x-jsrender">
    <tr>
    <td class="table__item-desc">
            <!-- <select class="form-select medicinebillCategories select2Selector" name="category_id[]" data-id="{{:uniqueId}}" required id="categoryChooseId{{:uniqueId}}" > -->
            <select class="form-select medicinebillCategories select2Selector medicineBillCategoriesId" name="category_id[]" data-id="{{:uniqueId}}" required id="medicineBillCategoriesId{{:uniqueId}}" >
            <option value="" disabled selected>Select your option</option>
                {{for medicinesCategories}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select>
        </td>
        <td class="table__item-desc">
            <!-- <select class="form-select purchaseMedicineId select2Selector" name="medicine[]" data-id="{{:uniqueId}}" required id="medicineChooseId{{:uniqueId}}" >
                <option value="" disabled selected>Select your option</option>
                {{for medicines}}
                    <option value="{{:key}}">{{:value}}</option>
                {{/for}}
            </select> -->
            <select class="form-select medicinePurchaseId purchaseMedicineId" id="medicine{{:uniqueId}}" name="medicine[]" data-id="{{:uniqueId}}" required >
                <!-- <option value="" disabled selected>Select your option</option> -->
            </select>
        </td>
        <!-- <td>
            <input class="form-control" placeholder="<?php echo __('messages.purchase_medicine.lot_no') ?>" required="" name="lot_no[]" type="text" id="lot_no{{:uniqueId}}">
        </td> -->
        <td>
            <input class="form-control bg-white medicineBillExpiryDate" placeholder="<?php echo __('messages.purchase_medicine.expiry_date') ?>" name="expiry_date[]"  id="expiry_date{{:uniqueId}}" type="text">
        </td>
        <td>
            <input class="form-control medicineBill-sale-price" required="" value='0.00' name="sale_price[]" id="medicine_sale_price{{:uniqueId}}" type="text" readonly>
        </td>
        <td>
            <div class="input-group">
                <input type="number" class="form-control medicineBill-quantity" required="" value='0' name="quantity[]"  id="quantity{{:uniqueId}}">
                <span class="input-group-text ms-0 available_qty" id="availableQtyId{{:uniqueId}}">0</span>
            </div>
        </td>
            <td>
            <div class="input-group">
            <input type="number" class="form-control medicineBill-tax" value='0'  name="tax_medicine[]"  id="tax{{:uniqueId}}">
             <span class="input-group-text ms-0" id="amountTypeSymbol">%</span>
            </div>
        </td>
                <td>
            <input type="number" class="form-control medicine-bill-amount" readonly required="" value='0.00' name="amount[]" id="amount{{:uniqueId}}">
        </td>purchaseMedicineTemplate
        <td class="text-center">
            <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
               class="delete-medicine-bill-item  btn px-1 text-danger fs-3 pe-0">
                     <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>

</script>
