"use strict";

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'module-table'){
        $('#module_filter_status').select2({
            width:"100%",
        });
    }
});


listenChange(".settingStatus", function (event) {
    let moduleId = $(event.currentTarget).attr("data-id");
    updateSettingStatus(moduleId);
});

listenChange("#module_filter_status", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
    hideDropdownManually($("#moduleFilterBtn"), $(".dropdown-menu"));
});

listenClick("#settingResetFilter", function () {
    $("#module_filter_status").val(0).trigger("change");
    hideDropdownManually($("#moduleFilterBtn"), $(".dropdown-menu"));
});

function updateSettingStatus(id) {
    $.ajax({
        url: $("#sideBarModuleUrl").val() + "/" + id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                setTimeout(function () {
                    window.location.reload();
                }, 5000);
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
    });
}
