document.addEventListener("DOMContentLoaded", loadMedicineCreateData);

("use strict");

function loadMedicineCreateData() {
    listenClick(".showMedicineBtn", function (event) {
        event.preventDefault();
        let medicineId = $(event.currentTarget).attr("data-id");
        renderMedicineData(medicineId);
    });

    function renderMedicineData(id) {
        $.ajax({
            url: $("#medicinesShowModal").val() + "/" + id,
            type: "GET",
            success: function (result) {
                if (result.success) {
                    $("#showMedicineName").text(result.data.name);
                    $("#showMedicineBrand").text(result.data.brand_name);
                    $("#showMedicineCategory").text(result.data.category_name);
                    $("#showMedicineSaltComposition").text(
                        result.data.salt_composition
                    );
                    $("#showMedicineSellingPrice").text(
                        result.data.selling_price
                    );
                    $("#showMedicineBuyingPrice").text(
                        result.data.buying_price
                    );
                    $("#showMedicineQuanity").text(
                        addCommas(result.data.quantity)
                    );
                    $("#showMedicineAvailableQuanity").text(
                        addCommas(result.data.available_quantity)
                    );
                    $("#showMedicineSideEffects").text(
                        result.data.side_effects
                    );
                    moment.locale($("#medicineLanguage").val());
                    let createDate = moment(result.data.created_at);
                    $("#showMedicineCreatedOn").text(createDate.fromNow());
                    $("#showMedicineUpdatedOn").text(
                        moment(result.data.updated_at).fromNow()
                    );
                    $("#showMedicineDescription").text(result.data.description);
                    setValueOfEmptySpan();
                    $("#showMedicine").appendTo("body").modal("show");
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    }
}

listenClick(".deleteMedicineBtn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    medicineDeleteItem(
        route("check.use.medicine", id),
        "",
        $("#Medicine").val()
    );
});

window.medicineDeleteItem = function (
    url,
    tableId = null,
    header,
    callFunction = null
) {
    $.ajax({
        url: url,
        type: "GET",
        success: function (result) {
            if (result.success) {
                let popUpText =
                    result.data.result == true
                        ? Lang.get("js.delete_medicine")
                        : $(".confirmVariable").val() + header + "?";
                swal({
                    title: $(".deleteVariable").val() + "!",
                    text: popUpText,
                    icon: $(".sweetAlertIcon").val(),
                    buttons: {
                        confirm:
                            $(".yesVariable").val() +
                            "," +
                            $(".deleteVariable").val(),
                        cancel:
                            $(".noVariable").val() +
                            "," +
                            $(".cancelVariable").val(),
                    },
                }).then((popResult) => {
                    if (popResult) {
                        deleteMedicineAjax(
                            $("#indexMedicineUrl").val() + "/" + result.data.id,
                            (tableId = null),
                            header,
                            (callFunction = null)
                        );
                    }
                });
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
};

function deleteMedicineAjax(url, tableId = null, header, callFunction = null) {
    $.ajax({
        url: url,
        type: "DELETE",
        dataType: "json",
        success: function (obj) {
            if (obj.success && obj.data) {
                swal({
                    title: obj.message,
                    text: $(".confirmVariable").val() + header + "?",
                    icon: sweetAlertIcon,
                    timer: 3000,
                    buttons: {
                        confirm:
                            $(".yesVariable").val() +
                            "," +
                            $(".deleteVariable").val(),
                        cancel:
                            $(".noVariable").val() +
                            "," +
                            $(".cancelVariable").val(),
                    },
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "json",
                            data: { canDeleteCheck: "yes" },
                            success: function (obj) {},
                            error: function (data) {
                                swal({
                                    title: "",
                                    text: data.responseJSON.message,
                                    confirmButtonColor: "#009ef7",
                                    icon: "error",
                                    timer: 5000,
                                    buttons: {
                                        confirm: $(".okVariable").val(),
                                    },
                                });
                            },
                        });
                    }
                });
            }
            if (obj.success && !obj.data) {
                Livewire.dispatch("resetPage");
                swal({
                    icon: "success",
                    title: $(".deletedVariable").val(),
                    confirmButtonColor: "#f62947",
                    text: header + " " + $(".hasBeenDeletedVariable").val(),
                    timer: 2000,
                    buttons: {
                        confirm: $(".okVariable").val(),
                    },
                });
                if (callFunction) {
                    eval(callFunction);
                }
            }
        },
        error: function (data) {
            swal({
                title: "",
                text: data.responseJSON.message,
                confirmButtonColor: "#009ef7",
                icon: "error",
                timer: 5000,
                buttons: {
                    confirm: $(".okVariable").val(),
                },
            });
        },
    });
}
