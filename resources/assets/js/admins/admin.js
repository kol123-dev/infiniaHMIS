// document.addEventListener("DOMContentLoaded", loadAdminData);

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'admin-table'){
        $("#admin_filter_status").select2({
            width: "100%",
        });
    }
});

listenChange("#admin_filter_status", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});

listen("click", "#accountResetFilter", function () {
    $("#admin_filter_status").val(0).trigger("change");
    hideDropdownManually($("#adminFilterBtn"), $(".dropdown-menu"));
});
