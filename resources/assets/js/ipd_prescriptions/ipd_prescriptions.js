document.addEventListener("DOMContentLoaded", loadIpdPrescriptionData);

function loadIpdPrescriptionData() {
    if (
        !$("#editIpdPrescriptionForm").length &&
        !$("#addIpdPrescriptionForm").length
    ) {
        return;
    }
    $(".ipdDoseDuration,.ipdDoseInterval,.prescriptionMedicineMealId").select2({
        width: "100%",
    });
}

listen("click", ".deleteIpdPrescriptionBtn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#showIpdPrescriptionUrl").val() + "/" + id,
        "",
        $("#ipdPrescription").val()
    );
});

const dropdownToSelect2 = (selector) => {
    if (selector === "#ipdPrescriptionItemTemplate") {
        $(".ipdCategoryId").select2({
            placeholder: Lang.get("js.select_category"),
            width: "100%",
            dropdownParent: $("#addIpdPrescriptionModal"),
        });
        $(
            ".ipdDoseDuration,.ipdDoseInterval,.prescriptionMedicineMealId"
        ).select2({
            width: "100%",
        });
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#editIpdPrescriptionModal"),
        });
    } else {
        $(".ipdCategoryId").select2({
            placeholder: Lang.get("js.select_category"),
            width: "100%",
            dropdownParent: $("#editIpdPrescriptionModal"),
        });
        $(
            ".ipdDoseDuration,.ipdDoseInterval,.prescriptionMedicineMealId"
        ).select2({
            width: "100%",
        });
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#editIpdPrescriptionModal"),
        });
    }
};

dropdownToSelect2("#ipdPrescriptionItemTemplate");

const medicineSelect2 = (selector) => {
    if (selector === "addIpdPrescriptionModal") {
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#addIpdPrescriptionModal"),
        });
    } else {
        $(".medicineId").select2({
            placeholder: Lang.get("js.select_medicine"),
            width: "100%",
            dropdownParent: $("#editIpdPrescriptionModal"),
        });
    }
};

listenClick("#addPrescriptionItem, #addPrescriptionItemOnEdit", function () {
    const itemSelector = parseInt($(this).data("edit"))
        ? "#editIpdPrescriptionItemTemplate"
        : "#ipdPrescriptionItemTemplate";
    const tbodyItemSelector = parseInt($(this).data("edit"))
        ? ".edit-ipd-prescription-item-container"
        : ".ipd-prescription-item-container";
    let uniqueId = $("#showIpdUniqueId").val();
    let data = {
        medicineCategories: JSON.parse($("#showMedicineCategories").val()),
        ipdDoseDuration: JSON.parse($(".ipdPrescriptionDurations").val()),
        ipdDoseInterval: JSON.parse($(".ipdPrescriptionIntervals").val()),
        meals: JSON.parse($(".ipdPrescriptionMeals").val()),
        uniqueId: uniqueId,
    };
    let ipdPrescriptionItemHtml = prepareTemplateRender(itemSelector, data);
    $(tbodyItemSelector).append(ipdPrescriptionItemHtml);
    dropdownToSelect2(itemSelector);
    uniqueId++;
    $("#showIpdUniqueId").val(uniqueId);

    resetIpdPrescriptionItemIndex(parseInt($(this).data("edit")));
});

const resetIpdPrescriptionItemIndex = (itemMode) => {
    const itemSelector = itemMode
        ? "#editIpdPrescriptionItemTemplate"
        : "#ipdPrescriptionItemTemplate";
    const tbodyItemSelector = itemMode
        ? ".edit-ipd-prescription-item-container"
        : ".ipd-prescription-item-container";
    const itemNo = itemMode
        ? ".edit-prescription-item-number"
        : ".prescription-item-number";

    let index = 1;
    $(tbodyItemSelector + ">tr").each(function () {
        $(this).find(itemNo).text(index);
        index++;
    });
    let uniqueId = $("#showIpdUniqueId").val();
    if (index - 1 == 0) {
        let data = {
            medicineCategories: JSON.parse($("#showMedicineCategories").val()),
            ipdDoseDuration: JSON.parse($(".ipdPrescriptionDurations").val()),
            ipdDoseInterval: JSON.parse($(".ipdPrescriptionIntervals").val()),
            meals: JSON.parse($(".ipdPrescriptionMeals").val()),
            uniqueId: uniqueId,
        };
        let ipdPrescriptionItemHtml = prepareTemplateRender(itemSelector, data);
        $(tbodyItemSelector).append(ipdPrescriptionItemHtml);
        dropdownToSelect2(itemSelector);
        uniqueId++;
    }
};

listenClick(".editIpdPrescriptionBtn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let ipdPrescriptionId = event.currentTarget.dataset.id;
    renderOpdPrescriptionData(ipdPrescriptionId);
});

listenClick(
    ".deleteIpdPrescription, .deleteIpdPrescriptionOnEdit",
    function () {
        $(this).parents("tr").remove();
        resetIpdPrescriptionItemIndex(parseInt($(this).data("edit")));
    }
);

listenChange(".ipdCategoryId", function (e, rData) {

    let currentRow = $(this).closest("tr");
    let medicineId = currentRow.find(".medicineId");
    let AvailbleQty = currentRow.find(".availableQty");
    let AvailbleQtyDiv = currentRow.find(".medicinqtyclass");

    if ($(this).val() !== "") {
        $.ajax({
            url: $("#showMedicinesListUrl").val(),
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
                    if ($(".modal").hasClass("show")) {
                        medicineSelect2($(".modal.fade.show").attr("id"));
                    }
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
});

listenChange(".medicineId", function () {
    let medicineId = $(this).val();
    let currentRow = $(this).closest("tr");
    let AvailbleQty = currentRow.find(".availableQty");
    let AvailbleQtyDiv = currentRow.find(".medicinqtyclass");
    $(AvailbleQty).removeClass('text-danger');
    $(AvailbleQty).removeClass('text-success');

    $.ajax({
        url: route("available.medicine.quantity", medicineId),
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

function renderOpdPrescriptionData(id) {
    $.ajax({
        url: $("#showIpdPrescriptionUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let medicineQty = result.data.medicines_qty;
                let ipdPrescriptionData = result.data.ipdPrescription;
                let ipdPrescriptionItemsData = result.data.ipdPrescriptionItems;
                $("#ipdEditPrescriptionId").val(ipdPrescriptionData.id);
                $("#editHeaderNote").val(ipdPrescriptionData.header_note);
                $("#editFooterNote").val(ipdPrescriptionData.footer_note);
                $.each(ipdPrescriptionItemsData, function (i, v) {
                    $("#addPrescriptionItemOnEdit").trigger("click");
                    let rowId = $("#showIpdUniqueId").val() - 1;

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
                        .find("[data-id='" + rowId + "']")
                        .val(v.category_id)
                        .trigger("change", [{ medicineId: v.medicine_id }]);
                    $(document)
                        .find("[data-dosage-id='" + rowId + "']")
                        .val(v.dosage);
                    $(document)
                        .find("[data-dose-duration-id='" + rowId + "']")
                        .val(v.day)
                        .trigger("change", [{ ipdDoseDuration: v.day }]);
                    $(document)
                        .find("[data-dose-interval-id='" + rowId + "']")
                        .val(v.dose_interval)
                        .trigger("change", [
                            { ipdDoseInterval: v.dose_interval },
                        ]);
                    $(document)
                        .find("[data-meal-id='" + rowId + "']")
                        .val(v.time)
                        .trigger("change", [
                            { prescriptionMedicineMealId: v.time },
                        ]);
                    $(document)
                        .find("[data-instruction-id='" + rowId + "']")
                        .val(v.instruction);
                });

                let index = 1;
                $(".edit-ipd-prescription-item-container>tr").each(function () {
                    $(this).find(".prescription-item-number").text(index);
                    index++;
                });

                $("#editIpdPrescriptionModal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenClick(".printIpdPrescription", function () {
    let divToPrint = document.getElementById("DivIdToPrint");
    let newWin = window.open("", "Print-Window");
    newWin.document.open();
    newWin.document.write(
        '<html><link href="' +
            $("#showIpdBootstrapUrl").val() +
            '" rel="stylesheet" type="text/css"/>' +
            '<body onload="window.print()">' +
            divToPrint.innerHTML +
            "</body></html>"
    );
    newWin.document.close();
    setTimeout(function () {
        newWin.close();
    }, 1000000);
});

listenHiddenBsModal("#addIpdPrescriptionModal", function () {
    $("#medicineDiv1").find("small").text("").end().css("margin-top", "0px");
    resetModalForm("#addIpdPrescriptionForm", "#validationErrorsBox");
    $("#ipdPrescriptionTbl").find("tr:gt(1)").remove();
    $(".ipdCategoryId").val("");
    $(".ipdCategoryId").trigger("change");
    $(".availableQty").text("");
    $(".medicinqtyclass").css("width",'').css("margin-top",'');
});

listenShownBsModal("#addIpdPrescriptionModal", function () {
    medicineSelect2(".medicineId");
    dropdownToSelect2("#ipdPrescriptionItemTemplate");
});

listenHiddenBsModal("#editIpdPrescriptionModal", function () {
    $("#medicineDiv1").find("small").text("").end().css("margin-top", "0px");
    $("#editIpdPrescriptionTbl").find("tr:gt(0)").remove();
    resetModalForm("#editIpdPrescriptionForm", "#editIpdPrescriptionErrorsBox");
});

listenClick(".viewIpdPrescription", function (event) {
    let ipdPrescriptionShowId = event.currentTarget.dataset.id;
    $.ajax({
        url: $("#showIpdPrescriptionUrl").val() + "/" + ipdPrescriptionShowId,
        type: "get",
        beforeSend: function () {
            screenLock();
        },
        success: function (result) {
            $("#ipdPrescriptionViewData").html(result);
            $("#showIpdPrescriptionModal").modal("show");
            ajaxCallCompleted();
        },
        complete: function () {
            screenUnLock();
        },
    });
});

listenSubmit("#addIpdPrescriptionForm", function (event) {
    event.preventDefault();
    if (checkOpdMedicine() !== true) {
        return false;
    }
    let loadingButton = jQuery(this).find("#btnIpdPrescriptionSave");
    loadingButton.button("loading");
    let data = {
        formSelector: $(this),
        url: $("#showIpdPrescriptionCreateUrl").val(),
        type: "POST",
    };
    newRecord(data, loadingButton, "#addIpdPrescriptionModal");
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

listenSubmit("#editIpdPrescriptionForm", function (event) {
    event.preventDefault();
    if (checkOpdMedicine() !== true) {
        return false;
    }
    let loadingButton = jQuery(this).find("#btnEditIpdPrescriptionSave");
    loadingButton.button("loading");
    let id = $("#ipdEditPrescriptionId").val();
    let url = $("#showIpdPrescriptionUrl").val() + "/" + id;
    let data = {
        formSelector: $(this),
        url: url,
        type: "POST",
        // 'tableSelector': tableName,
    };
    editRecord(data, loadingButton, "#editIpdPrescriptionModal");
});
