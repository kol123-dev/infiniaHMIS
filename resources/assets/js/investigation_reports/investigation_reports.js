Livewire.hook("element.init", ({component}) => {
    if(component.name == 'investigation-report-table'){
        $("#investigationHead").select2({
            width: "100%",
        });
    }
});

listenClick("#investigationResetFilter", function () {
    $("#investigationHead").val(0).trigger("change");
    hideDropdownManually($("#investigationFilterBtn"), $(".dropdown-menu"));
});

listenClick(".deleteInvestigationBtn", function (event) {
    let investigationReportId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#indexInvestigationReportUrl").val() + "/" + investigationReportId,
        "",
        $("#investigationReport").val()
    );
});
listenChange("#investigationHead", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});
