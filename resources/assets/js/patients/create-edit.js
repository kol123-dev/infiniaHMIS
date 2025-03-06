document.addEventListener("DOMContentLoaded", loadPatientData);

function loadPatientData() {
    var customDate = $('#customFieldDate').val();
    var customDateTime = $('#customFieldDateTime').val();

    if (!$("#createPatientForm").length && !$("#editPatientForm").length) {
        return;
    }
    $(".patientBirthDate").flatpickr({
        maxDate: new Date(),
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    $('#customFieldDate').flatpickr({
        defaultDate: customDate ? customDate : new Date(),
        dateFormat: 'Y-m-d',
        locale: $('.userCurrentLanguage').val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    $('#customFieldDateTime').flatpickr({
        enableTime: true,
        defaultDate: customDateTime ? customDateTime : new Date(),
        dateFormat: "Y-m-d H:i",
        locale: $('.userCurrentLanguage').val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });
}

listenKeyup(".patientFacebookUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup(".patientTwitterUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup(".patientInstagramUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup(".patientLinkedInUrl", function () {
    this.value = this.value.toLowerCase();
});

function validateForm(formSelector) {
    var isValid = true;
    var form = $(formSelector);

    if ($(".error-msg").text() !== "") {
        $(".phoneNumber").focus();
        return false;
    }

    let facebookUrl = $(".patientFacebookUrl").val();
    let twitterUrl = $(".patientTwitterUrl").val();
    let instagramUrl = $(".patientInstagramUrl").val();
    let linkedInUrl = $(".patientLinkedInUrl").val();

    let facebookExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{2,3}\.)?)facebook.[a-z]{2,3}\/?.*/i
    );
    let twitterExp = new RegExp(
        /^(https?:\/\/)?((m{1}\.)?)?((w{2,3}\.)?)twitter\.[a-z]{2,3}\/?.*/i
    );
    let instagramUrlExp = new RegExp(
        /^(https?:\/\/)?((w{2,3}\.)?)instagram.[a-z]{2,3}\/?.*/i
    );
    let linkedInExp = new RegExp(
        /^(https?:\/\/)?((w{2,3}\.)?)linkedin\.[a-z]{2,3}\/?.*/i
    );

    form.find('.dynamic-field').each(function () {
        var fieldValue = $(this).val();
        var fieldLabel = $(this).closest('.form-group').find('label').text().replace(':', '').trim();

        if ($(this).is(':input[type="text"], :input[type="number"], textarea')) {
            if (!fieldValue || fieldValue.trim() === '') {
                displayErrorMessage(fieldLabel + ' ' + Lang.get('js.field_required'));
                isValid = false;
                return false;
            }
        } else if ($(this).is(':input[type="checkbox"]')) {
            if (!$(this).is(':checked')) {
                displayErrorMessage(fieldLabel + ' ' + Lang.get('js.field_required'));
                isValid = false;
                return false;
            }
        } else if ($(this).is('select')) {
            if (!fieldValue && $(this).val().length === 0 && fieldValue.trim() === '') {
                displayErrorMessage('Please select ' + fieldLabel);
                isValid = false;
                return false;
            }
        }
    });

    Lang.setLocale($('.userCurrentLanguage').val())
    let facebookCheck =
        facebookUrl == ""
            ? true
            : facebookUrl.match(facebookExp)
                ? true
                : false;
    if (!facebookCheck) {
        displayErrorMessage(Lang.get("js.validate_facebook_url"));
        return false;
    }
    let twitterCheck =
        twitterUrl == "" ? true : twitterUrl.match(twitterExp) ? true : false;
    if (!twitterCheck) {
        displayErrorMessage(Lang.get("js.validate_twitter_url"));
        return false;
    }
    let instagramCheck =
        instagramUrl == ""
            ? true
            : instagramUrl.match(instagramUrlExp)
                ? true
                : false;
    if (!instagramCheck) {
        displayErrorMessage(Lang.get("js.validate_instagram_url"));
        return false;
    }
    let linkedInCheck =
        linkedInUrl == ""
            ? true
            : linkedInUrl.match(linkedInExp)
                ? true
                : false;
    if (!linkedInCheck) {
        displayErrorMessage(Lang.get("js.validate_linkedin_url"));
        return false;
    }

    event.preventDefault();

    if (isValid) {
        form.submit();
    }
}

listenClick('#btnSave', function () {
    validateForm('#createPatientForm');
});

listenClick('#editPatientSave', function () {
    validateForm('#editPatientForm');
});

$("#createPatientForm, #editPatientForm")
    .find("input:text:visible:first")
    .focus();

listenClick(".remove-patient-image", function () {
    defaultImagePreview(".previewImage", 1);
});

listenChange(".patientProfileImage", function () {
    let extension = isValidImage($(this), "#patientErrorBox");

    if (!isEmpty(extension) && extension != false) {
        $("#patientErrorBox").html("").hide();
        displayDocument(this, "#patientErrorBox", extension);
    } else {
        $(this).val("");
        $("#patientErrorBox").removeClass("d-none hide");
        $("#patientErrorBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=patientErrorBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

listenChange(".editPatientImage", function () {
    let extension = isValidImage($(this), "#editPatientErrorsBox");

    if (!isEmpty(extension) && extension != false) {
        $("#editPatientErrorsBox").html("").hide();
        displayDocument(this, "#patientErrorBox", extension);
    } else {
        $(this).val("");
        $("#editPatientErrorsBox").removeClass("d-none hide");
        $("#editPatientErrorsBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=editPatientErrorsBox]").focus();
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
