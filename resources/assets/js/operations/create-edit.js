window.addEventListener("DOMContentLoaded", loadOperationData);

function loadOperationData() {
    if (
        !$("#operationCategoryId").length &&
        !$("#editOperationCategoryId").length
    ) {
        return;
    }

    $("#operationCategoryId").select2({
        dropdownParent: $("#add_operations_modal"),
    });

    $("#editOperationCategoryId").select2({
        dropdownParent: $("#edit_operations_modal"),
    });
}

listenSubmit("#addOperationForm", function (event) {
    event.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        url: route("operations.store"),
        type: "POST",
        data: data,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#addOperationForm")[0].reset();
                $("#add_operations_modal").modal("hide");
                Livewire.dispatch("refresh");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenHiddenBsModal("#add_operations_modal", function () {
    resetModalForm("#addOperationForm", "#operationErrorsBox");
    $("#operationCategoryId").val("").trigger("change.select2");
});

listenClick(".updateOperationBtn", function () {
    var id = $(this).data("id");
    $.ajax({
        url: $(".editOperationURL").val() + "/" + id,
        type: "GET",
        success: function (result) {
            $("#editOperationID").val(result.data.id);
            $("#editOperationName").val(result.data.name);
            $("#editOperationCategoryId")
                .val(result.data.operation_category_id)
                .trigger("change.select2");
        },
    });
});

listenSubmit("#editOperationForm", function (event) {
    event.preventDefault();
    var data = $(this).serialize();
    var id = $("#editOperationID").val();
    $.ajax({
        url: $(".editOperationURL").val() + "/" + id + "/update",
        type: "POST",
        data: data,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#edit_operations_modal").modal("hide");
                Livewire.dispatch("refresh");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick(".deleteOperationBtn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        $(".editOperationURL").val() + "/" + id,
        "",
        $(".operation").val()
    );
});
