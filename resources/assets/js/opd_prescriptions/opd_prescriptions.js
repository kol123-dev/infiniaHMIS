document.addEventListener('DOMContentLoaded', loadOpdPrescriptionData);

function loadOpdPrescriptionData() {
    if(!$('#addOpdPrescriptionForm').length){
        return;
    }
    $('.opdCategoryId,.medicineId,.opdDoseDuration,.opdDoseInterval,.opdPrescriptionMedicineMealId').select2({
        width: "100%",
    })
}

// Dropdown To Select2
const dropdownToSelect2 = (selector) => {
    if (selector === "#opdPrescriptionItemTemplate") {
        $(".opdCategoryId").select2({
            placeholder: Lang.get("js.select_category"),
            width: "100%",
            dropdownParent: $("#addOpdPrescriptionModal"),
        });
        $(
            ".opdDoseDuration,.opdDoseInterval,.opdPrescriptionMedicineMealId"
        ).select2({
            width: "100%",
        });
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#addOpdPrescriptionModal"),
        });
    } else {
        $(".opdCategoryId").select2({
            placeholder: Lang.get("js.select_category"),
            width: "100%",
            dropdownParent: $("#editOpdPrescriptionModal"),
        });
        $(
            ".opdDoseDuration,.opdDoseInterval,.opdPrescriptionMedicineMealId"
        ).select2({
            width: "100%",
        });
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#editOpdPrescriptionModal"),
        });
    }
};


// Dropdown To Select2
const medicineSelect2 = (selector) => {
    if (selector === "addOpdPrescriptionModal") {
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#addOpdPrescriptionModal"),
        });
    } else {
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#editOpdPrescriptionModal"),
        });
    }
};

// Add OPD Prescription Item
listenClick("#addOpdPrescriptionItem, #addOpdPrescriptionItemOnEdit", function () {
    const itemSelector = parseInt($(this).data("edit"))
        ? "#editOpdPrescriptionItemTemplate"
        : "#opdPrescriptionItemTemplate";
    const tbodyItemSelector = parseInt($(this).data("edit"))
        ? ".edit-opd-prescription-item-container"
        : ".opd-prescription-item-container";
    let uniqueId = $("#showOpdUniqueId").val();
    let data = {
        medicineCategories: JSON.parse($("#showOpdMedicineCategories").val()),
        opdDoseDuration: JSON.parse($(".opdPrescriptionDurations").val()),
        opdDoseInterval: JSON.parse($(".opdPrescriptionIntervals").val()),
        meals: JSON.parse($(".opdPrescriptionMeals").val()),
        uniqueId: uniqueId,
    };
    let opdPrescriptionItemHtml = prepareTemplateRender(itemSelector, data);
    $(tbodyItemSelector).append(opdPrescriptionItemHtml);
    dropdownToSelect2(itemSelector);
    uniqueId++;
    $("#showOpdUniqueId").val(uniqueId);

    resetOpdPrescriptionItemIndex(parseInt($(this).data("edit")));
});

// Reset OPD Presciption Item
const resetOpdPrescriptionItemIndex = (itemMode) => {
    const itemSelector = itemMode
        ? "#editOpdPrescriptionItemTemplate"
        : "#opdPrescriptionItemTemplate";
    const tbodyItemSelector = itemMode
        ? ".edit-opd-prescription-item-container"
        : ".opd-prescription-item-container";
    const itemNo = itemMode
        ? ".edit-opd-prescription-item-number"
        : ".opd-prescription-item-number";
    let index = 1;
    $(tbodyItemSelector + ">tr").each(function () {
        $(this).find(itemNo).text(index);
        index++;
    });
    let uniqueId = $("#showOpdUniqueId").val();
    if (index - 1 == 0) {
        let data = {
            medicineCategories: JSON.parse($("#showOpdMedicineCategories").val()),
            opdDoseDuration: JSON.parse($(".opdPrescriptionDurations").val()),
            opdDoseInterval: JSON.parse($(".opdPrescriptionIntervals").val()),
            meals: JSON.parse($(".opdPrescriptionMeals").val()),
            uniqueId: uniqueId,
        };
        let opdPrescriptionItemHtml = prepareTemplateRender(itemSelector, data);
        $(tbodyItemSelector).append(opdPrescriptionItemHtml);
        dropdownToSelect2(itemSelector);

        uniqueId++;
    }
};

// Delete OPD Prescription Item
listenClick(".deleteOpdPrescription,.deleteOpdPrescriptionOnEdit", function () {
    $(this).parents("tr").remove();
    resetOpdPrescriptionItemIndex(parseInt($(this).data("edit")))
});

// Get Medicines Lists
listenChange('.opdCategoryId', function(e, rData) {

    let currentRow = $(this).closest("tr");
    let medicineId = currentRow.find(".medicineId");
    let AvailbleQty = currentRow.find(".availableQty");
    let AvailbleQtyDiv = currentRow.find(".medicinqtyclass");

    if ($(this).val() !== "") {
        $.ajax({
            url: $("#showOpdMedicinesListUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    medicineId.empty();
                    medicineId.removeAttr("disabled");
                    $(AvailbleQty).text('');
                    $(AvailbleQtyDiv).css({ "margin-top": "0px" });
                    $(medicineId).append(
                        $(
                            '<option value="">' + Lang.get("js.select_medicine") + "</option>"
                        )
                    );
                    $.each(data.data, function (i, v) {
                        medicineId.append(
                            $("<option></option>").attr("value", i).text(v)
                        );
                    });
                    if (typeof rData != "undefined") {
                        medicineId
                            .val(rData.medicineId)
                            .trigger("change.select2");
                    }
                } else {
                    medicineId.append(
                        $("<option></option>").text(
                            Lang.get("js.select_medicine")
                        )
                    );
                }
            },
        });
    }
    medicineId.empty();
    medicineId.prop("disabled", true);
})

// Get Medician Available Quantity
listenChange(".medicineId", function () {
    let medicineId = $(this).val();
    let currentRow = $(this).closest("tr");
    let AvailbleQty = currentRow.find(".availableQty");
    let AvailbleQtyDiv = currentRow.find(".medicinqtyclass");
    $(AvailbleQty).removeClass('text-danger');
    $(AvailbleQty).removeClass('text-success');

    $.ajax({
        url: route("opd.available.medicine.quantity", medicineId),
        type: "GET",
        success: function (data) {
            if (data.data.length !== 0) {
                let availableQuantity = data.data.available_quantity;
                let availbleQtyText = `${Lang.get(
                    "js.available_quantity"
                )}: ${availableQuantity}`;
                let availbleQtyClass =
                    availableQuantity == 0 ? "text-danger" : "text-success";

                $(AvailbleQty)
                    .text(availbleQtyText)
                    .addClass(availbleQtyClass);
                $(AvailbleQtyDiv).css({ "margin-top": "22px" });
            }
        },
    });
});


// Add Opd Prescription
listenSubmit("#addOpdPrescriptionForm", function (event) {
    event.preventDefault();
    if (checkOpdMedicine() !== true) {
        return false;
    }
    let loadingButton = jQuery(this).find("#btnOpdPrescriptionSave");
    loadingButton.button("loading");
    let data = {
        formSelector: $(this),
        url: $("#showOpdPrescriptionCreateUrl").val(),
        type: "POST",
    };
    newRecord(data, loadingButton, "#addOpdPrescriptionModal");
});

function checkOpdMedicine() {
    let result = true;
    $(".medicineId").each(function xyz() {
        if ($(this).val() == "Select Medicine") {
            displayErrorMessage(
                Lang.get("js.medicine_required")
            );
            result = false;
            return false;
        }
    });
    return result;
}


// Reset Modal Form
listenHiddenBsModal("#addOpdPrescriptionModal", function () {
    $("#medicineDiv1").find("small").text("").end().css("margin-top", "0px");
    resetModalForm("#addOpdPrescriptionForm", "#OpdPrescriptionErrorsBox");
    $("#opdPrescriptionTbl").find("tr:gt(1)").remove();
    $(".opdCategoryId").val("");
    $(".opdCategoryId").trigger("change");
    $(".availableQty").text("");
    $(".medicinqtyclass").css("width",'').css("margin-top",'');
});

listenShownBsModal("#addOpdPrescriptionModal", function () {
    medicineSelect2(".medicineId");
    dropdownToSelect2("#opdPrescriptionItemTemplate");
});

listenHiddenBsModal("#editOpdPrescriptionModal", function () {
    $("#medicineDiv1").find("small").text("").end().css("margin-top", "0px");
    $("#editOpdPrescriptionTbl").find("tr:gt(0)").remove();
    resetModalForm("#editOpdPrescriptionForm", "#editOpdPrescriptionErrorsBox");
});

// Delete Opd Presciption
listenClick(".deleteOpdPrescriptionBtn", function (event) {
    let id = $(event.currentTarget).attr("data-id");

    deleteItem(
        $("#showOpdPrescriptionUrl").val() + "/" + id,
        "",
        $("#opdPrescription").val()
    );
});

// Edit Opd Prescription
listenClick(".editOpdPrescriptionBtn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let opdPrescriptionId = event.currentTarget.dataset.id;
    renderOpdPrescriptionData(opdPrescriptionId);
});

// Render Opd Prescription
function renderOpdPrescriptionData(id) {
    $.ajax({
        url: $("#showOpdPrescriptionUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let medicineQty = result.data.medicines_qty;
                let opdPrescriptionData = result.data.opdPrescription;
                let opdPrescriptionItemsData = result.data.opdPrescriptionItems;

                $("#opdEditPrescriptionId").val(opdPrescriptionData.id);
                $("#editOpdHeaderNote").val(opdPrescriptionData.header_note);
                $("#editOpdFooterNote").val(opdPrescriptionData.footer_note);

                $.each(opdPrescriptionItemsData, function (i, v) {
                    $("#addOpdPrescriptionItemOnEdit").trigger("click");
                    let rowId = $("#showOpdUniqueId").val() - 1;

                    let AvailbleQtyDiv = "#medicineDiv" + rowId;

                    var element = $(document).find(
                        "[data-avlMedicine-id='" + rowId + "']"
                    );

                    var availableQuantity = v.medicine.available_quantity;
                    var message =
                        Lang.get("js.available_quantity") +
                        ": " +
                        availableQuantity;

                    element
                        .text(message)
                        .addClass(
                            availableQuantity == 0
                                ? "text-danger"
                                : "text-success"
                        );
                    $(AvailbleQtyDiv).css({ "margin-top": "22px" });

                    $(document)
                        .find("[data-id='" + rowId + "']")
                        .val(v.category_id)
                        .trigger("change", [{ medicineId: v.medicine_id }]);
                    $(document)
                        .find("[data-dosage-id='" + rowId + "']")
                        .val(v.dosage);
                    $(document)
                        .find("[data-dose-duration-id='" + rowId + "']")
                        .val(v.day)
                        .trigger("change", [{ opdDoseDuration: v.day }]);
                    $(document)
                        .find("[data-dose-interval-id='" + rowId + "']")
                        .val(v.dose_interval)
                        .trigger("change", [
                            { opdDoseInterval: v.dose_interval },
                        ]);
                    $(document)
                        .find("[data-meal-id='" + rowId + "']")
                        .val(v.time)
                        .trigger("change", [
                            { opdPrescriptionMedicineMealId: v.time },
                        ]);
                    $(document)
                        .find("[data-instruction-id='" + rowId + "']")
                        .val(v.instruction);
                });
                let index = 1;
                $(".edit-opd-prescription-item-container>tr").each(function () {
                    $(this).find(".opd-prescription-item-number").text(index);
                    index++;
                });

                $("#editOpdPrescriptionModal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

// Edit/Update Opd Presciption
listenSubmit("#editOpdPrescriptionForm", function (event) {
    event.preventDefault();
    if (checkOpdMedicine() !== true) {
        return false;
    }
    let loadingButton = jQuery(this).find("#btnEditOpdPrescriptionSave");

    loadingButton.button("loading");
    let id = $("#opdEditPrescriptionId").val();

    let url = $("#showOpdPrescriptionUrl").val() + "/" + id;
    let data = {
        formSelector: $(this),
        url: url,
        type: "POST",
    };
    editRecord(data, loadingButton, "#editOpdPrescriptionModal");

    // $("#editOpdPrescriptionModal").modal("hide");
});

// View Opd Presciption
listenClick(".viewOpdPrescription", function (event) {
    let opdPrescriptionId = event.currentTarget.dataset.id;
    $.ajax({
        url: $("#showOpdPrescriptionUrl").val() + "/" + opdPrescriptionId,
        type: "get",
        beforeSend: function () {
            screenLock();
        },
        success: function (result) {
            $("#opdPrescriptionViewData").html(result);
            $("#showOpdPrescriptionModal").modal("show");
            ajaxCallCompleted();
        },
        complete: function () {
            screenUnLock();
        },
    });
});
