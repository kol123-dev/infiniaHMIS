"use strict";

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'receptionist-table'){
        $('#receptionist_filter_status').select2({
            width: '100%',
        });
    }
});

window.updateReceptionistStatus = function (id) {
    $.ajax({
        url: $("#receptionistUrl").val() + "/" + +id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
};

listenClick(".delete-receptionist-btn", function (event) {
    let receptionistId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#receptionistUrl").val() + "/" + receptionistId,
        "#receptionistsTbl",
        $("#Receptionist").val()
    );
});

listenChange(".receptionistStatus", function (event) {
    let receptionistId = $(event.currentTarget).attr("data-id");
    updateReceptionistStatus(receptionistId);
});

listenChange("#receptionist_filter_status", function () {
    Livewire.dispatch("changeFilter", { status: $(this).val() });
});

listen("click", "#receptionistResetFilter", function () {
    $("#receptionist_filter_status").val(0).trigger("change");
    hideDropdownManually($("#receptionistsFilterBtn"), $(".dropdown-menu"));
});
