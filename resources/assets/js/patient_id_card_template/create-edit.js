document.addEventListener('DOMContentLoaded', loadPatientIdCardData)

function loadPatientIdCardData(){
    Lang.setLocale($(".userCurrentLanguage").val());
}

listenChange("#emailStatus, #phone, #address, #bloodGroup, #dob, #patientUniqueId", function () {
    let status = $(this).prop("checked") ? 1 : 0;
    let id = $(this).data("id");
    let name = $(this).attr("name");
    $.ajax({
        type: "post",
        url: route("patient.id.card.status", id),
        data: { status: status, name: name },
        success: function (data) {
            if (data.success) {
                displaySuccessMessage(data.message);
                Livewire.dispatch("refresh");
            }
        },
    });
});

listenChange("#color", function () {
    let id = $(this).data("id");
    let color = $(this).val();
    $.ajax({
        type: "post",
        url: route("patient.id.card.status", id),
        data: { color: color },
        success: function (data) {
            if (data.success) {
                displaySuccessMessage(data.message);
                Livewire.dispatch("refresh");
            }
        },
    });
});

listenChange(
    "#createEmailStatus, #createPhoneStatus, #createAddressStatus, #createBloodGroupStatus, #createDobStatus, #createUniqueIdStatus, #CreateColor",
    function () {
        let name = $(this).attr("id");
        let color = $("#CreateColor").val();

        switch (name) {
            case "CreateColor":
                $(".smart-card-header").css("background-color", color);
                break;
            case "createEmailStatus":
                $("#ShowCreateEmail").toggleClass("d-none");
                break;
            case "createPhoneStatus":
                $("#ShowCreatePhone").toggleClass("d-none");
                break;
            case "createAddressStatus":
                $("#ShowCreateAddress").toggleClass("d-none");
                break;
            case "createBloodGroupStatus":
                $("#ShowCreateBloodGroup").toggleClass("d-none");
                break;
            case "createDobStatus":
                $("#ShowCreateDob").toggleClass("d-none");
                break;
            case "createUniqueIdStatus":
                $("#ShowUniqueId").toggleClass("d-none");
                break;
            default:
                "";
        }
    }
);
