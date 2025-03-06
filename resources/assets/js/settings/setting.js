document.addEventListener("DOMContentLoaded", loadSettingData);

function loadSettingData() {
    Lang.setLocale($(".userCurrentLanguage").val());

    if (!$("#generalCurrencyType").length) {
        return;
    }
    $("#generalCurrencyType").select2({
        width: "100%",
    });
    $("#settingLang").select2({
        width: "100%",
    });
    initializeDefaultCountryCode();

    let openAICheckbox = $('#opneAiEnable').is(':checked')

    if (openAICheckbox) {
        $('.opne-ai-div').removeClass('d-none')
    } else {
        $('.opne-ai-div').addClass('d-none')
    }
}

function initializeDefaultCountryCode() {
    let countryCode = $("#countryPhone");
    if (!countryCode.length) {
        return false;
    }

    let input = document.querySelector("#countryPhone");
    (errorMsg = document.querySelector(".error-msg")),
        (validMsg = document.querySelector(".valid-msg"));

    let errorMap = [
        Lang.get("js.invalid_number"),
        Lang.get("js.invalid_country_code"),
        Lang.get("js.too_short"),
        Lang.get("js.too_long"),
    ];
    // initialise plugin
    let intl = window.intlTelInput(input, {
        initialCountry: "IN",
        separateDialCode: true,
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {}, "jsonp").always(
                function (resp) {
                    let countryCode = resp && resp.country ? resp.country : "";
                    success(countryCode);
                }
            );
        },
        utilsScript: "../../public/assets/js/inttel/js/utils.min.js",
    });
    let getCode =
        intl.selectedCountryData["name"] +
        " +" +
        intl.selectedCountryData["dialCode"];
    $("#countryPhone").val(getCode);

    let reset = function () {
        input.classList.remove("error");
    };

    input.addEventListener("blur", function () {
        reset();
        if (input.value.trim()) {
            if (intl.isValidNumber()) {
                validMsg.classList.remove("d-none");
            } else {
                input.classList.add("error");
                let errorCode = intl.getValidationError();
                // errorMsg.innerHTML = errorMap[errorCode]
                errorMsg.classList.remove("d-none");
            }
        }
    });

    // on keyup / change flag: reset
    input.addEventListener("change", reset);
    input.addEventListener("keyup", reset);

    $(document).on(
        "blur keyup change countrychange",
        "#countryPhone",
        function () {
            let getCode = intl.selectedCountryData["dialCode"];
            let getCountry = intl.selectedCountryData["iso2"];
            $("#countryCode").val(getCode);
            $("#countryName").val(getCountry);
        }
    );
}

listenChange(".generalAppLogo", function () {
    let extension = isValidSettingLogo($(this), "#generalValidationErrorsBox");
    if (!isEmpty(extension) && extension != false) {
        $("#generalValidationErrorsBox").html("").hide();
        displayDocument(this, "#generalPreviewImage", extension);
    } else {
        $(this).val("");
        $("#generalValidationErrorsBox").removeClass("d-none hide");
        $("#generalValidationErrorsBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=generalValidationErrorsBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

listen('change', '#opneAiEnable', function () {
    let openAICheckbox = $('#opneAiEnable').is(':checked')
    if (openAICheckbox) {
        $('.opne-ai-div').removeClass('d-none')
    } else {
        $('.opne-ai-div').addClass('d-none')
    }
})


listenChange(".generalFavicon", function () {
    let extension = isValidSettingLogo($(this), "#settingValidationErrorsBox");

    if (!isEmpty(extension) && extension != false) {
        $("#generalValidationErrorsBox").html("").hide();
        displayDocument(this, "#generalPreviewImage", extension);
    } else {
        $(this).val("");
        $("#generalValidationErrorsBox").removeClass("d-none hide");
        $("#generalValidationErrorsBox")
            .text(Lang.get("js.validate_image_type"))
            .show();
        $("[id=generalValidationErrorsBox]").focus();
        $("html, body").animate({ scrollTop: "0" }, 500);
        $(".alert").delay(5000).slideUp(300);
    }
});

function isValidSettingLogo(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["jpg", "png", "jpeg"]) == -1) {
        // $(inputSelector).val('');
        // $(validationMessageSelector).removeClass('d-none');
        // $(validationMessageSelector).html('The image must be a file of type: jpg, jpeg, png.').show();
        // displayErrorMessage('The image must be a file of type: jpg, jpeg, png.')
        return false;
    }
    $(validationMessageSelector).hide();
    return true;
}

function displaySettingLogo(input, selector) {
    let displayPreview = true;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (image.height != 60 && image.width != 90) {
                    $(selector).val("");
                    $("#generalValidationErrorsBox").removeClass("d-none");
                    $("#generalValidationErrorsBox")
                        .html($("#editGeneralImageValidation").val())
                        .show();
                    return false;
                }
                $(selector).attr("src", e.target.result);
                displayPreview = true;
            };
        };
        if (displayPreview) {
            reader.readAsDataURL(input.files[0]);
            $(selector).show();
        }
    }
}

listenKeyup("#generalFacebookUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup("#generalTwitterUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup("#generalInstagramUrl", function () {
    this.value = this.value.toLowerCase();
});
listenKeyup("#generalLinkedInUrl", function () {
    this.value = this.value.toLowerCase();
});

listenSubmit("#createSetting", function (event) {
    // event.preventDefault();

    if ($(".error-msg").text() !== "") {
        $("#generalPhoneNumber").focus();
        return false;
    }

    let facebookUrl = $("#generalFacebookUrl").val();
    let twitterUrl = $("#generalTwitterUrl").val();
    let instagramUrl = $("#generalInstagramUrl").val();
    let linkedInUrl = $("#generalLinkedInUrl").val();
    let openAICheckbox = $('#opneAiEnable').is(':checked')

    if (openAICheckbox && $('#openAIKey').val().trim() == '') {
        displayErrorMessage(Lang.get('js.open_ai_key'))
        return false
    }
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
    // $('#createSetting')[0].submit();

    return true;
});
