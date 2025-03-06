document.addEventListener("DOMContentLoaded", loadPurchaseMedicineCreate);
let uniquePrescriptionId = "";

function loadPurchaseMedicineCreate() {
    if (!$("#purchaseUniqueId").length) {
        return;
    }
    $(".purchaseMedicineExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });
    $("#paymentMode").select2({
        width: "100%",
    });
}

listenClick(".add-medicine-btn-purchase", function () {
    uniquePrescriptionId = $("#purchaseUniqueId").val();
    let data = {
        medicines: JSON.parse($(".associatePurchaseMedicines").val()),
        uniqueId: uniquePrescriptionId,
    };
    let prescriptionMedicineHtml = prepareTemplateRender(
        "#purchaseMedicineTemplate",
        data
    );
    $(".prescription-medicine-container").append(prescriptionMedicineHtml);
    dropdownToSelecte2(".purchaseMedicineId");
    $(".purchaseMedicineExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });
    uniquePrescriptionId++;
    $("#purchaseUniqueId").val(uniquePrescriptionId);
});
const dropdownToSelecte2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("js.select_medicine"),
        width: "100%",
    });
};

listenChange(".purchaseMedicineId", function () {
    let medicineId = $(this).val();
    let uniqueId = $(this).attr("data-id");
    let salePriceId = "#sale_price" + uniqueId;
    let buyPriceId = "#purchase_price" + uniqueId;
    if (medicineId == "") {
        $(salePriceId).val("0.00");
        $(buyPriceId).val("0.00");

        return false;
    }
    $.ajax({
        type: "get",
        url: route("get-medicine", medicineId),
        success: function (result) {
            $(salePriceId).val(result.data.selling_price.toFixed(2));
            $(buyPriceId).val(result.data.buying_price.toFixed(2));
        },
    });
});

listenKeyup(
    ".purchase-quantity,.purchase-price,purchase-quantity,.purchase-tax,.purchase-discount",
    function () {
        let value = $(this).val();
        $(this).val(value.replace(/[^0-9\.]/g, ""));
        var currentRow = $(this).closest("tr");
        let currentqty = currentRow.find(".purchase-quantity").val();
        let price = currentRow.find(".purchase-price").val();
        let currentamount = parseFloat(price * currentqty);
        currentRow.find(".purchase-amount").val(currentamount.toFixed(2));

        let taxEle = $(".purchase-tax");
        let elements = $(".purchase-amount");
        let total = 0.0;
        let totalTax = 0;
        let netAmount = 0;
        let discount = 0;
        let amount = 0;
        for (let i = 0; i < elements.length; i++) {
            total += parseFloat(elements[i].value);
            discount = $(".purchase-discount").val();
            if (taxEle[i].value != 0 && taxEle[i].value != "") {
                if (taxEle[i].value > 99) {
                    let taxAmount = taxEle[i].value.slice(0, -1);
                    currentRow.find(".purchase-tax").val(taxAmount);
                    displayErrorMessage(Lang.get("js.validate_tax"));
                    $("#discountAmount").val(discount);
                    return false;
                }
                totalTax += (elements[i].value * taxEle[i].value) / 100;
            } else {
                amount += parseFloat(elements[i].value);
            }
        }
        discount = discount == "" ? 0 : discount;
        netAmount = parseFloat(total) + parseFloat(totalTax);
        netAmount = parseFloat(netAmount) - parseFloat(discount);
        if (discount > total && $(this).hasClass("purchase-discount")) {
            discount = discount.slice(0, -1);
            displayErrorMessage(Lang.get("js.validate_discount"));
            $("#discountAmount").val(discount);
            return false;
        }
        if (discount > total) {
            netAmount = 0;
        }

        $("#total").val(total.toFixed(2));
        $("#purchaseTaxId").val(totalTax.toFixed(2));
        $("#netAmount").val(netAmount.toFixed(2));
    }
);

listenClick(".delete-purchase-medicine-item", function () {
    let currentRow = $(this).closest("tr");
    let currentRowAmount = currentRow.find(".purchase-amount").val();
    let currentRowTax = currentRow.find(".purchase-tax").val();
    let currentTaxAmount =
        parseFloat(currentRowAmount) * parseFloat(currentRowTax / 100);
    let updatedTax =
        parseFloat($("#purchaseTaxId").val()) - parseFloat(currentTaxAmount);

    $("#purchaseTaxId").val(updatedTax.toFixed(2));
    let updatedTotalAmount =
        parseFloat($("#total").val()) - parseFloat(currentRowAmount);
    $("#total").val(updatedTotalAmount.toFixed(2));
    let amountSubfromNetAmt =
        parseFloat(currentTaxAmount) + parseFloat(currentRowAmount);

    let updateNetAmount =
        parseFloat($("#netAmount").val()) - parseFloat(amountSubfromNetAmt);
    $("#netAmount").val(updateNetAmount.toFixed(2));
    $(this).parents("tr").remove();
});

listenSubmit("#purchaseMedicineFormId", function (e) {
    e.preventDefault();

    let y = $("#purchaseUniqueId").val() - 1;
    let tx = 1;
    $(".saveBtnPurchaseMedicne").prop("disabled", true);
    let saveBtnDisFalse = $(".saveBtnPurchaseMedicne").prop("disabled", false);

    for (let i = 1; i <= y; i++) {
        let medicinID = "#medicineChooseId" + i;
        let taxId = "tax" + i;

        if (typeof $(taxId).val() != "undefined") {
            if ($(taxId).val() == null || $(taxId).val() == "") {
                tx = 0;
            }
        }

        if (typeof $(medicinID).val() != "undefined") {
            if ($(medicinID).val() == null || $(medicinID).val() == "") {
                displayErrorMessage(Lang.get("js.select_medicine"));
                saveBtnDisFalse
                return false;
            }
        }

        let lotNum = "#lot_no" + i;
        if (typeof $(lotNum).val() != "undefined") {
            if ($(lotNum).val() == null || $(lotNum).val() == "") {
                displayErrorMessage(Lang.get("js.lot_number"));
                saveBtnDisFalse
                return false;
            }
        }

        let expiryDate = "#expiry_date" + i;
        if (typeof $(expiryDate).val() != "undefined") {
            if ($(expiryDate).val() == null || $(expiryDate).val() == "") {
                displayErrorMessage(Lang.get("js.expiry_date_required"));
                saveBtnDisFalse
                return false;
            }
        }

        let salePrice = "#sale_price" + i;
        if (typeof $(salePrice).val() != "undefined") {
            if ($(salePrice).val() == null || $(salePrice).val() == "") {
                displayErrorMessage(
                    Lang.get("js.sale_price")
                );
                saveBtnDisFalse
                return false;
            }
        }

        let purchasePrice = "#purchase_price" + i;
        if (typeof $(purchasePrice).val() != "undefined") {
            if (
                $(purchasePrice).val() == null ||
                $(purchasePrice).val() == ""
            ) {
                displayErrorMessage(
                    Lang.get("js.purchase_price")
                );
                saveBtnDisFalse
                return false;
            } else if ($(purchasePrice).val() == 0) {
                displayErrorMessage(
                    Lang.get("js.validate_quantity")
                );
                saveBtnDisFalse
                return false;
            }
        }

        let quantityID = "#quantity" + i;
        if (typeof $(quantityID).val() != "undefined") {
            if ($(quantityID).val() == null || $(quantityID).val() == "") {
                displayErrorMessage(
                    Lang.get("js.quantity_required")
                );
                saveBtnDisFalse
                return false;
            } else if ($(quantityID).val() == 0) {
                displayErrorMessage(
                    Lang.get("js.validate_quantity")
                );
                saveBtnDisFalse
                return false;
            }
        }

        let amount = "#amount" + i;
        if (typeof $(amount).val() != "undefined") {
            if($(amount).val() == "" || $(amount).val() == null || $(amount).val() == 0) {
                displayErrorMessage(Lang.get("js.amount_not_zero"));
                saveBtnDisFalse
                return false;
            }
        }
    }

    let netAmount = "#netAmount";
    if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(
            Lang.get("js.net_amount_not_empty")
        );
        saveBtnDisFalse
        return false;
    } else if ($(netAmount).val() == 0) {
        displayErrorMessage(
            Lang.get("js.net_amount_not_zero")
        );
        saveBtnDisFalse
        return false;
    }

    if (
        tx == 0 &&
        ($("#purchaseTaxId").val() == null || $("#purchaseTaxId").val() == "")
    ) {
        displayErrorMessage(
            Lang.get("js.tax_amount_not_zero_or_empty")
        );
        saveBtnDisFalse
        return false;
    }

    let paymentType = "#paymentMode";
    if($(paymentType).val() == null || $(paymentType).val() == "") {
        displayErrorMessage(Lang.get("js.payment_type"));
        validate = false;
        saveButtonDisableOff;
        return false;
    }

    $.ajax({
        url: route('medicine-purchase.store'),
        type: 'POST',
        data: $('#purchaseMedicineFormId').serialize(),
        success: function(result){
            if(result.data == null){
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    location.href = route('medicine-purchase.index');
                }, 1000);
                $(".saveBtnPurchaseMedicne").prop("disabled", false);
            }else{
                if(result.data.payment_type == 5){
                    let sessionId = result.data[0].sessionId;

                    stripe.redirectToCheckout({
                        sessionId: sessionId,
                    });
                }
                if (result.data.payment_type == 2) {
                    $.ajax({
                        url: route("purchase.medicine.razorpay.init"),
                        type: "POST",
                        data: $("#purchaseMedicineFormId").serialize(),
                        success: function (data) {
                            if (data.success) {
                                let { id, amount, currency_symbol } = data.data;
                                options.order_id = id;
                                options.amount = amount;
                                options.currency_symbol = currency_symbol;

                                let rzp = new Razorpay(options);
                                rzp.open();
                            }
                        },
                        error: function (error) {
                            displayErrorMessage(error.responseJSON.message);
                        },
                    });
                }
                if (result.data.payStackData != null) {
                    if (result.data.payStackData.payment_type == 3) {
                        window.location.replace(
                            route("purchase.medicine.paystack.init", {
                                data: result.data.payStackData,
                                net_amount: result.data.payStackData.net_amount,
                                purchase_no:
                                    result.data.payStackData.purchase_no,
                            })
                        );
                    }
                }
                if (result.data.payment_type == 4) {
                    window.location.href = result.data.url;
                }
                if(result.data.payment_type == 6){
                    window.location.href = result.data.url;
                }
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
            $(".saveBtnPurchaseMedicne").prop("disabled", false);
        },
    });
});

listenClick(".purchaseMedicineDelete", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("medicine-purchase.destroy", id),
        "",
        Lang.get("js.purchase_medicine")
    );
});
