document.addEventListener("DOMContentLoaded", loadAdminData);

function loadAdminData() {
    $("#adminBirthDate").flatpickr({
        format: "YYYY-MM-DD",
        useCurrent: true,
        sideBySide: true,
        maxDate: new Date(),
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    $("#editAdminBirthDate").flatpickr({
        format: "YYYY-MM-DD",
        useCurrent: true,
        sideBySide: true,
        maxDate: new Date(),
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    listenClick(".delete-admin-btn", function (event) {
        let adminId = $(event.currentTarget).attr("data-id");
        deleteItem($("#adminURL").val() + "/" + adminId, "", $("#admin").val());
    });
}

listenSubmit("#createAdminForm, #editAdminForm", function () {
    if ($(".error-msg").text() !== "") {
        $(".phoneNumber").focus();
        return false;
    }
});

listenChange(".admin-status", function (event) {
    let accountantId = $(event.currentTarget).attr("data-id");
    updateAccountantStatus(accountantId);
});

function updateAccountantStatus(id) {
    $.ajax({
        url: $("#adminURL").val() + "/" + +id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenChange(".adminProfileImage", function () {
    let extension = isValidImage($(this), "#adminErrorBox");
    if (!isEmpty(extension) && extension != false) {
        $("#adminErrorBox").html("").hide();
        displayDocument(this, "#customValidationErrorsBox", extension);
    } else {
        $(this).val("");
        $("#adminErrorBox").removeClass("d-none hide");
        $("#adminErrorBox").text(Lang.get("js.validate_image_type")).show();
        $("[id=adminErrorBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

listenChange(".adminProfileImage", function () {
    let extension = isValidImage($(this), "#editAdminErrorBox");

    if (!isEmpty(extension) && extension != false) {
        $("#editAdminErrorBox").html("").hide();
        displayDocument(this, "#customValidationErrorsBox", extension);
    } else {
        $(this).val("");
        $("#editAdminErrorBox").removeClass("d-none hide");
        $("#editAdminErrorBox").text(Lang.get("js.validate_image_type")).show();
        $("[id=editAdminErrorBox]").focus();
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
