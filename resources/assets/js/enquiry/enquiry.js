Livewire.hook("element.init", ({component}) => {
    if(component.name == 'enquiry-table'){
        $("#enquiriesHead").select2({
            width: "100%",
        });
    }
});

listenChange(".enquiryStatus", function () {
    let enquiryId = $(this).attr("data-id");
    updateEnquiryStatus(enquiryId);
});

function updateEnquiryStatus(id) {
    $.ajax({
        url: $("#indexEnquiryUrl").val() + "/" + +id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
    });
}

listenClick("#resetEnquiryFilter", function () {
    $("#enquiriesHead").val(2).trigger("change");
    hideDropdownManually($("#enquiriesFilterBtn"), $(".dropdown-menu"));
});

listenChange("#enquiriesHead", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});
