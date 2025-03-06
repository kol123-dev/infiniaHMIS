// document.addEventListener('DOMContentLoaded', loadIpdPatientData)

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'ipd-patient-table'){
        loadIpdPatientData();
        $('#ipd_patients_filter_status').select2({
            width:"100%",
        });
    }
});

function loadIpdPatientData() {
    if (!$("#ipd_patients_filter_status").length) {
        return;
    }
    $("#ipd_patients_filter_status").select2();
}

listenChange("#ipd_patients_filter_status", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});

listenClick("#ipdResetFilter", function () {
    $("#ipd_patients_filter_status").val("0").trigger("change");
    hideDropdownManually(
        $("#ipdPatientDepartmentFilterBtn"),
        $(".dropdown-menu")
    );
});

listen("click", ".deleteIpdDepartmentBtn", function (event) {
    let ipdPatientId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#indexIpdPatientUrl").val() + "/" + ipdPatientId,
        "",
        $("#ipdPatientDepartment").val()
    );
});
