document.addEventListener("DOMContentLoaded", loadSaleMedicineCreate);
let uniquePrescriptionId = "";
Lang.setLocale($(".userCurrentLanguage").val());
function loadSaleMedicineCreate() {
    if (!$("#medicineUniqueId").length) {
        return;
    }

    $(".medicine_bill_date").flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        dateFormat: "Y-m-d H:i",
    });

    $(".edit_medicine_bill_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    $(".medicineBillExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });

    $(".medicine-payment-mode").select2({
        width: "100%",
    });
    $(".medicineBillCategoriesId").select2({
        width: "100%",
    });
}

listenChange(".medicineBillCategoriesId", function () {
    let categoryId = $(this).val();

    let currentRow = $(this).closest("tr");
    let medicineId = currentRow.find(".purchaseMedicineId");
    let availableQTY = currentRow.find(".available_qty");

    $(availableQTY).text(0);

    if (categoryId == "") {
        $(medicineId).find("option").remove();
        $(medicineId).append(
            $("<option></option>")
                .attr("placeholder", "")
                .text(Lang.get("js.select_medicine"))
        );

        return false;
    }
    $.ajax({
        type: "get",
        url: route("get-medicine-category", categoryId),
        success: function (result) {
            let array = result.data.medicine;
            $(medicineId).find("option").remove();
            $(medicineId).attr("required", true);
            $(medicineId).append(
                $(
                    '<option value="">' +
                    Lang.get("js.select_medicine") +
                    "</option>"
                )
            );
            $.each(array, function (key, value) {
                $(medicineId).append(
                    $("<option></option>").attr("value", key).text(value)
                );
            });
        },
    });
});

listenChange(".medicinePurchaseId", function () {
    var currentRow = $(this).closest("tr");
    let medicineId = $(this).val();
    let uniqueId = $(this).attr("data-id");
    let salePriceId = "#medicine_sale_price" + uniqueId;
    var availableQtyId = "#availableQtyId" + uniqueId;
    let expiryDate = "#expiry_date" + uniqueId;

    if (medicineId == "" || medicineId == Lang.get("js.select_medicine")) {
        $(salePriceId).val("0.00");
        return false;
    }
    $.ajax({
        type: "get",
        url: route("get-medicine", medicineId),
        success: function (result) {
            $(salePriceId).val(result.data.selling_price.toFixed(2));
            $(availableQtyId).text(result.data.available_quantity);
            if (result.data.expiry_date !== null && result.data.expiry_date != "") {
                $(expiryDate).val(format(result.data.expiry_date, 'YYYY-MM-DD'));
                // $(expiryDate).val(result.data.expiry_date);
            }
            let currentqty = currentRow.find(".medicineBill-quantity").val();
            let price = currentRow.find(".medicineBill-sale-price").val();
            let currentamount = parseFloat(price * currentqty);

            currentRow
                .find(".medicine-bill-amount")
                .val(currentamount.toFixed(2));
            let taxEle = $(".medicineBill-tax");
            let elements = $(".medicine-bill-amount");
            let total = 0.0;
            let totalTax = 0;
            let netAmount = 0;
            let discount = 0;
            let amount = 0;
            for (let i = 0; i < elements.length; i++) {
                total += parseFloat(elements[i].value);
                discount = $(".medicineBill-discount").val();
                if (taxEle[i].value != 0 && taxEle[i].value != "") {
                    totalTax += (elements[i].value * taxEle[i].value) / 100;
                } else {
                    amount += parseFloat(elements[i].value);
                }
            }
            discount = discount == "" ? 0 : discount;
            netAmount = parseFloat(total) + parseFloat(totalTax);
            netAmount = parseFloat(netAmount) - parseFloat(discount);
            if (discount > total && $(this).hasClass("medicineBill-discount")) {
                discount = discount.slice(0, -1);
                displayErrorMessage(Lang.get("js.validate_discount"));
                $("#discountAmount").val(discount);
                return false;
            }
            if (discount > total) {
                netAmount = 0;
            }
            $("#total").val(total.toFixed(2));
            $("#medicineTotalTaxId").val(totalTax.toFixed(2));
            $("#netAmount").val(netAmount.toFixed(2));
        },
    });
});

listenClick(".add-medicine-btn-medicine-bill", function () {
    uniquePrescriptionId = $("#medicineUniqueId").val();
    let data = {
        medicinesCategories: JSON.parse(
            $("#showMedicineCategoriesMedicineBill").val()
        ),
        medicines: JSON.parse($(".associatePurchaseMedicines").val()),
        uniqueId: uniquePrescriptionId,
    };
    let prescriptionMedicineHtml = prepareTemplateRender(
        "#medicineBillTemplate",
        data
    );
    $(".medicine-bill-container").append(prescriptionMedicineHtml);
    dropdownToSelecte2(".medicinePurchaseId");
    dropdownToSelecteCategories2(".medicinebillCategories");
    expiryDateFlatePicker(".medicinebillCategories");

    $(".purchaseMedicineExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });
    uniquePrescriptionId++;
    $("#medicineUniqueId").val(uniquePrescriptionId);
});
const dropdownToSelecte2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("js.select_medicine"),
        width: "100%",
    });
};
const dropdownToSelecteCategories2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("js.select_category"),
        width: "100%",
    });
};
const expiryDateFlatePicker = (selector) => {
    $(".medicineBillExpiryDate").flatpickr({
        minDate: new Date(),
        dateFormat: "Y-m-d",
    });
};
listenKeyup(
    ".medicineBill-quantity,.medicineBill-price,.medicineBill-tax,.medicineBill-discount,.medicineBill-sale-price",
    function () {
        let value = $(this).val();
        $(this).val(value.replace(/[^0-9\.]/g, ""));
        var currentRow = $(this).closest("tr");
        let currentqty = currentRow.find(".medicineBill-quantity").val();
        let price = currentRow.find(".medicineBill-sale-price").val();
        let currentamount = parseFloat(price * currentqty);
        currentRow.find(".medicine-bill-amount").val(currentamount.toFixed(2));

        let taxEle = $(".medicineBill-tax");
        let elements = $(".medicine-bill-amount");
        let total = 0.0;
        let totalTax = 0;
        let netAmount = 0;
        let discount = 0;
        let amount = 0;
        var qty = $(".medicineBill-quantity");
        var PreviousQty = $(".previous-quantity");
        for (let i = 0; i < elements.length; i++) {
            total += parseFloat(elements[i].value);
            discount = $(".medicineBill-discount").val();
            if ($("#medicineBillStatus").val() == 1) {
                if (parseInt(qty[i].value) > parseInt(PreviousQty[i].value)) {
                    let qtyRollback = qty[i].value.slice(0, -1);
                    currentRow.find(".medicineBill-quantity").val(qtyRollback);
                    currentqty = currentRow
                        .find(".medicineBill-quantity")
                        .val();
                    price = currentRow.find(".medicineBill-sale-price").val();
                    currentamount = parseFloat(price * currentqty);
                    currentRow
                        .find(".medicine-bill-amount")
                        .val(currentamount.toFixed(2));
                    displayErrorMessage(Lang.get("js.update_quantity"));
                    return false;
                }
            }
            if (taxEle[i].value != 0 && taxEle[i].value != "") {
                if (taxEle[i].value > 99) {
                    let taxAmount = taxEle[i].value.slice(0, -1);
                    currentRow.find(".medicineBill-tax").val(taxAmount);
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
        if (discount > total && $(this).hasClass("medicineBill-discount")) {
            discount = discount.slice(0, -1);
            displayErrorMessage(Lang.get("js.validate_discount"));
            $("#discountAmount").val(discount);
            return false;
        }
        if (discount > total) {
            netAmount = 0;
        }
        $("#total").val(total.toFixed(2));
        $("#medicineTotalTaxId").val(totalTax.toFixed(2));
        $("#netAmount").val(netAmount.toFixed(2));
    }
);

listenSubmit("#createMedicinebillFormId", function (e) {
    e.preventDefault();
    let netAmount = "#netAmount";
    var validate = true;
    let y = $("#medicineUniqueId").val() - 1;
    $(".saveBtnMedicneBill").prop("disabled", true);
    let saveBtnDisOff = $(".saveBtnMedicneBill").prop("disabled", false);

    let patientId = $("#prescriptionPatientId");
    if (patientId.val() == "" || patientId.val() == null) {
        displayErrorMessage(Lang.get("js.select_patient"));
        validate = false;
        savebtnDisOff;
        return false;
    }

    let billDate = $("#medicine_bill_date");
    if (billDate.val() == "" || billDate.val() == null) {
        displayErrorMessage(Lang.get("js.bill_date"));
        validate = false;
        savebtnDisOff;
        return false;
    }

    for (let i = 1; i <= y; i++) {

        let taxId = "tax" + i;
        if (typeof $(taxId).val() != "undefined") {
            if ($(taxId).val() == null || $(taxId).val() == "") {
                tx = 0;
            }
        }

        let medicineCategory = "#medicineBillCategoriesId" + i;
        if ($(medicineCategory).val() == null || $(medicineCategory).val() == "") {
            displayErrorMessage(Lang.get("js.select_medicine_category"));
            validate = false;
            savebtnDisOff;
            return false;
        }

        let medicine = "#medicine" + i;
        if ($(medicine).val() == null || $(medicine).val() == "") {
            displayErrorMessage(Lang.get("js.select_medicine"));
            validate = false;
            savebtnDisOff;
            return false;
        }

        let expiryDate = "#expiry_date" + i;
        if (typeof $(expiryDate).val() != "undefined") {
            if ($(expiryDate).val() == null || $(expiryDate).val() == "") {
                displayErrorMessage(Lang.get("js.expiry_date_required"));
                validate = false;
                saveBtnDisOff;
                return false;
            }
        }

        let medicineSalePrice = "#medicine_sale_price" + i;
        if ($(medicineSalePrice).val() == null || $(medicineSalePrice).val() == "") {
            displayErrorMessage(Lang.get("js.enter_sale_price"));
            validate = false;
            savebtnDisOff;
            return false;
        }

        let medicineQuantity = "#quantity" + i;
        if ($(medicineQuantity).val() == null || $(medicineQuantity).val() == "" || $(medicineQuantity).val() == 0) {
            displayErrorMessage(Lang.get("js.quantity_greater_than_0"));
            validate = false;
            savebtnDisOff;
            return false;
        }

        let medicineAmount = "#amount" + i;
        if ($(medicineAmount).val() == null || $(medicineAmount).val() == "" || $(medicineAmount).val() == 0) {
            displayErrorMessage(Lang.get("js.amount_not_zero"));
            validate = false;
            savebtnDisOff;
            return false;
        }
    }

    if ($("#total").val() < $("#discountAmount").val()) {
        displayErrorMessage(Lang.get("js.discount_less_than_amount"));
        validate = false;
        savebtnDisOff;
        return false;
    }

    if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(Lang.get("js.net_amount_not_empty"));
        validate = false;
        return;
    }

    if ($(netAmount).val() == 0) {
        displayErrorMessage(Lang.get("js.net_amount_not_zero"));
        validate = false;
        return;
    }

    let paymentType = "#paymentMode";
    if ($(paymentType).val() == null || $(paymentType).val() == "") {
        displayErrorMessage(Lang.get("js.payment_type"));
        validate = false;
        savebtnDisOff;
        return false;
    }

    if (validate) {
        $.ajax({
            url: route("medicine-bills.store"),
            type: "POST",
            data: $("#createMedicinebillFormId").serialize(),
            success: function (result) {
                if (result.success) {
                    if (result.data != null) {
                        if (result.data.payment_type == 5) {
                            let sessionId = result.data[0].sessionId;
                            stripe
                                .redirectToCheckout({
                                    sessionId: sessionId,
                                })
                                .then((mainResult) =>
                                    manageAjaxErrors(mainResult)
                                );
                        }
                        if (result.data.payment_type == 2) {
                            $.ajax({
                                url: route("medicine.bill.razorpay.init"),
                                type: "POST",
                                data: $(
                                    "#createMedicinebillFormId"
                                ).serialize(),
                                success: function (result) {
                                    if (result.success) {
                                        let { id, net_amount } = result.data;
                                        options.order_id = id;
                                        options.net_amount = net_amount;

                                        let rzp = new Razorpay(options);
                                        rzp.open();
                                    }
                                },
                                error: function (error) {
                                    $("#addIpdPaymentModal").modal("hide");
                                    displayErrorMessage(
                                        error.responseJSON.message
                                    );
                                    livewire.emit("refresh");
                                },
                            });
                        }
                        if (result.data.payment_type == 4) {
                            window.location.href = result.data.url;
                        }
                        if (result.data.payStackData != null) {
                            if (result.data.payStackData.payment_type == 3) {
                                window.location.replace(
                                    route("medicine.bill.paystack.init", {
                                        data: result.data.payStackData,
                                        net_amount:
                                            result.data.payStackData.net_amount,
                                    })
                                );
                            }
                        }
                        if (result.data.payment_type == 6) {
                            window.location.href = result.data.url;
                        }
                    } else {
                        displaySuccessMessage(result.message);
                        setTimeout(() => {
                            window.location.href = route(
                                "medicine-bills.index"
                            );
                        }, 1500);
                        $(".saveBtnMedicneBill").prop("disabled", false);
                    }
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
                $(".saveBtnMedicneBill").prop("disabled", false);
            },
        });
    }
});

listenClick(".add-patient-modal", function () {
    $("#addPatientModal").appendTo("body").modal("show");
});

listenSubmit("#addPatientForm", function (e) {
    e.preventDefault();
    processingBtn("#addPatientForm", "#patientBtnSave", "loading");
    $("#patientBtnSave").attr("disabled", true);
    $.ajax({
        url: route("store.patient"),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $("#prescriptionPatientId").find("option").remove();
                $("#prescriptionPatientId").append(
                    $("<option></option>")
                        .attr("placeholder", "")
                        .text(Lang.get("js.select_patient"))
                );
                $.each(result.data, function (i, v) {
                    $("#prescriptionPatientId").append(
                        $("<option></option>").attr("value", i).text(v)
                    );
                });
                displaySuccessMessage(result.message);
                $("#addPatientModal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            $("#patientBtnSave").attr("disabled", false);
            processingBtn("#addPatientForm", "#patientBtnSave");
        },
    });
});

listenHiddenBsModal("#addPatientModal", function () {
    resetModalForm("#addPatientForm", "#patientErrorsBox");
});

listenClick(".medicine-bill-delete-btn", function (event) {
    let id = $(event.currentTarget).attr("data-id");

    deleteItem(
        route("medicine-bills.destroy", id),
        "",
        Lang.get("js.medicine_bill")
    );
});

listenSubmit("#MedicinebillForm", function (e) {
    e.preventDefault();

    let netAmount = "#netAmount";
    if (
        parseFloat($("#total").val()) < parseFloat($("#discountAmount").val())
    ) {
        displayErrorMessage(Lang.get("js.validate_discount"));
        return false;
    } else if ($(netAmount).val() == null || $(netAmount).val() == "") {
        displayErrorMessage(Lang.get("js.net_amount_not_empty"));
        return false;
    } else if ($(netAmount).val() == 0) {
        displayErrorMessage(Lang.get("js.net_amount_not_zero"));
        return false;
    } else if (
        $(".medicineBill-quantity").val() == 0 ||
        $(".medicineBill-quantity").val() == null ||
        $(".medicineBill-quantity").val() == ""
    ) {
        displayErrorMessage(Lang.get("js.quantity_cannot_be_zero"));
        return false;
    }
    $medicineBillId = $("#medicineBillId").val();
    $.ajax({
        url: route("medicine-bills.update", $medicineBillId),
        type: "post",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    Turbo.visit(route("medicine-bills.index")); // true
                }, 2000);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick(".delete-medicine-bill-item", function () {
    let currentRow = $(this).closest("tr");
    let currentRowAmount = currentRow.find(".medicine-bill-amount").val();
    let currentRowTax = currentRow.find(".medicineBill-tax").val();
    let currentTaxAmount =
        parseFloat(currentRowAmount) * parseFloat(currentRowTax / 100);
    let updatedTax =
        parseFloat($("#medicineTotalTaxId").val()) -
        parseFloat(currentTaxAmount);

    $("#medicineTotalTaxId").val(updatedTax.toFixed(2));
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
