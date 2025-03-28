document.addEventListener("DOMContentLoaded", loadEditPharmacistData);

function loadEditPharmacistData() {
    if (
        !$("#createPharmacistForm").length &&
        !$("#editPharmacistForm").length
    ) {
        return;
    }

    $(".pharmacistBloodGroup").select2({
        width: "100%",
    });
    let birthDate = $(".pharmacistBirthDate").flatpickr({
        dateFormat: "Y-m-d",
        useCurrent: false,
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });
    // birthDate.setDate(isEmpty($('#birthDate').val()) ? new Date() : $('#birthDate').val());
    birthDate.set("maxDate", new Date());
    if ($(".departmentId").length) {
        $(".departmentId").select2({
            width: "100%",
        });
    }
    $("#createPharmacistForm, #editPharmacistForm")
        .find("input:text:visible:first")
        .focus();
}
listenSubmit("#createPharmacistForm, #editPharmacistForm", function () {
    if ($(".error-msg").text() !== "") {
        $(".phoneNumber").focus();
        return false;
    }
});
listenClick(".remove-pharmacist-image", function () {
    defaultImagePreview(".previewImage", 1);
});

listenChange(".pharmacistProfileImage", function () {
    let extension = isValidImage($(this), "#pharmacistsErrorBox");

    if (!isEmpty(extension) && extension != false) {
        $("#pharmacistsErrorBox").html("").hide();
        displayDocument(this, "#pharmacistsErrorBox", extension);
    } else {
        $(this).val("");
        $("#pharmacistsErrorBox").removeClass("d-none hide");
        $("#pharmacistsErrorBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=pharmacistsErrorBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

listenChange(".editPharmacistProfileImage", function () {
    let extension = isValidImage($(this), "#editPharmacistErrorBox");

    if (!isEmpty(extension) && extension != false) {
        $("#editPharmacistErrorBox").html("").hide();
        displayDocument(this, "#pharmacistsErrorBox", extension);
    } else {
        $(this).val("");
        $("#editPharmacistErrorBox").removeClass("d-none hide");
        $("#editPharmacistErrorBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=editPharmacistErrorBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

function isValidImage(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["jpg", "png", "jpeg"]) == -1) {
        return false;
    }
    $(validationMessageSelector).hide();
    return true;
}
