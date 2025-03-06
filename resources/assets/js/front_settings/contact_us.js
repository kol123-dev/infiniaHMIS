document.addEventListener("DOMContentLoaded", loadFrontSettingContactData);

function loadFrontSettingContactData() {
    Lang.setLocale($(".userCurrentLanguage").val());

    if (!$("#contactUsGeneral").length) {
        return;
    }

    if (!$(".phoneNumber").length) {
        return false;
    }

    $("#contactUsGeneral").selectize();

    if ($("#g-recaptcha").length) {
        grecaptcha.render("g-recaptcha", {
            sitekey: $("#adminRecaptcha").val(),
        });
    }
}

listenSubmit("#enquiryCreateForm", function (e) {
    e.preventDefault();
    let response = "";
    if ($(".error-msg").text() !== "") {
        $(".phoneNumber").focus();
        return false;
    }
    $.ajax({
        url: $("#frontInquiryUrl").val(),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                // resetModalForm('#enquiryCreateForm')
                setTimeout(function () {
                    $("#enquiryCreateForm")[0].reset();
                    $(".error-msg").addClass("d-none");
                    $(".valid-msg").addClass("d-none");
                    var $select = $("#contactUsGeneral").selectize();
                    var control = $select[0].selectize;
                    control.setValue(1, true);
                    grecaptcha.reset();
                }, 5000);
            } else {
                displayErrorMessage(result.message);
                setTimeout(function () {
                    $("#enquiryCreateForm")[0].reset();
                    $(".contactUsGeneral").val(1).trigger("change");
                    grecaptcha.reset();
                }, 5000);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            grecaptcha.reset();
        },
        complete: function () {
            // setTimeout(
            //     function(){$(".general").val("").change();},
            //     5000
            // );
            // $('.general').val("")
        },
    });
});
