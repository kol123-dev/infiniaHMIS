Livewire.hook("element.init", ({component}) => {
    if(component.name == 'live-consultation-table'){
        $("#liveConsultationFilterStatusArr, .change-consultation-status").select2({
            width: "100%",
        });
    }
});



listenChange("#liveConsultationFilterStatusArr", function () {
    Livewire.dispatch("changeFilter", { statusFilter: $(this).val() });
});
listenClick("#consultationResetFilter", function () {
    $("#liveConsultationFilterStatusArr").val(0).trigger("change");
    hideDropdownManually($("#liveConsultationFilterBtn"), $(".dropdown-menu"));
});

listenChange(".consultation-type", function () {
    $(".consultation-type-number").val("").trigger("change");
});

listenChange(".patient-name", function () {
    $(".consultation-type").val("").trigger("change");
    $(".consultation-type-number").trigger("change");
});

listenChange(".platform-type", function () {
    let googleMeet = $(this).val();

    if (googleMeet == 2 && googleMeet != undefined) {
        $(".host-video-section").addClass("d-none");
        $(".participant-video-section").addClass("d-none");
    } else {
        $(".host-video-section").removeClass("d-none");
        $(".participant-video-section").removeClass("d-none");
    }
});
