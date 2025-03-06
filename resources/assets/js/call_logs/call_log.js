"use strict";

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'call-log-table'){
        $("#callType").select2({
            width: "100%",
        });
    }
});

listenClick("#callLogResetFilter", function () {
    $("#callType").val(0).trigger("change");
    hideDropdownManually($("#callTypeFilterBtn"), $(".dropdown-menu"));
});

listenClick(".call-log-delete-btn", function (event) {
    let callLogId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $(".callLogUrl").val() + "/" + callLogId,
        "",
        $("#callLogs").val()
    );
});
listenChange("#callType", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});
