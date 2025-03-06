"use strict";

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'lab-technician-table'){
        $('#technicianFilterStatus').select2({
            width: '100%',
        });
    }
});

listen("click", ".deleteTechnicianBtn", function (event) {
    let labTechnicianId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#labTechnicianURL").val() + "/" + labTechnicianId,
        "",
        $("#labTechnician").val()
    );
});

listenChange(".technicianStatus", function (event) {
    let labTechnicianId = $(event.currentTarget).attr("data-id");
    updateLabTechnicianStatus(labTechnicianId);
});

listenChange("#technicianFilterStatus", function () {
    Livewire.dispatch("changeFilter", { status: $(this).val() });
});

listen("click", "#technicianResetFilter", function () {
    $("#technicianFilterStatus").val(0).trigger("change");
    hideDropdownManually($("#labTechnicianFilterBtn"), $(".dropdown-menu"));
});

window.updateLabTechnicianStatus = function (id) {
    $.ajax({
        url: $("#labTechnicianURL").val() + "/" + id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
    });
};
