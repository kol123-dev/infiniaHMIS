// import Quill from 'quill';
document.addEventListener("DOMContentLoaded", loadFrontSettingCMSData);

function loadFrontSettingCMSData() {
    if (!$("#termConditionPrivacyPolicy").length) {
        return;
    }

    if (
        typeof $("#termConditionPrivacyPolicy").val() != "undefined" &&
        $("#termConditionPrivacyPolicy").val() == true
    ) {
        let quill1 = new Quill("#termConditionId", {
            modules: {
                toolbar: [
                    [
                        {
                            header: [1, 2, false],
                        },
                    ],
                    ["bold", "italic", "underline"],
                    ["image", "code-block"],
                ],
            },
            placeholder: Lang.get("js.terms_conditions"),
            theme: "snow", // or 'bubble'
        });
        quill1.on("text-change", function (delta, oldDelta, source) {
            if (quill1.getText().trim().length === 0) {
                quill1.setContents([{ insert: "" }]);
            }
        });

        let quill2 = new Quill("#privacyPolicyId", {
            modules: {
                toolbar: [
                    [
                        {
                            header: [1, 2, false],
                        },
                    ],
                    ["bold", "italic", "underline"],
                    ["image", "code-block"],
                ],
            },
            placeholder: Lang.get("js.privacy_policy"),
            theme: "snow", // or 'bubble'
        });
        quill2.on("text-change", function (delta, oldDelta, source) {
            if (quill2.getText().trim().length === 0) {
                quill2.setContents([{ insert: "" }]);
            }
        });

        let element = document.createElement("textarea");
        element.innerHTML = $(".termConditionData").val();
        quill1.root.innerHTML = element.value;

        element.innerHTML = $(".privacyPolicyData").val();
        quill2.root.innerHTML = element.value;

        listenSubmit("#termsAndCondition", function () {
            let element = document.createElement("textarea");
            let editor_content_1 = quill1.root.innerHTML;
            element.innerHTML = editor_content_1;
            let editor_content_2 = quill2.root.innerHTML;
            if (quill1.getText().trim().length === 0) {
                displayErrorMessage(Lang.get("js.the_terms_conditions_is_required"));
                return false;
            }

            if (quill2.getText().trim().length === 0) {
                displayErrorMessage(Lang.get("js.the_privacy_policy_is_required"));
                return false;
            }

            $("#termData").val(editor_content_1.toString());
            $("#privacyData").val(editor_content_2.toString());
        });
    }
}

listenChange(".homePageImage", function () {
    let extension = isValidCmsImage($(this), "#homeErrorsBox");
    if (!isEmpty(extension) && extension != false) {
        $("#homeErrorsBox").html("").hide();
        displayDocument(this, "#homePreviewImage", extension);
    }
});

listenChange(".homeDoctorImage", function () {
    let extension = isValidCmsImage($(this), "#homeErrorsBox");
    if (!isEmpty(extension) && extension != false) {
        $("#homeErrorsBox").html("").hide();
        displayDocument(this, "#homeDoctorPreviewImage", extension);
    }
});

function isValidCmsImage(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["jpg", "png", "jpeg"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector).removeClass("d-none");
        $(validationMessageSelector)
            .html(Lang.get("js.validate_image_type"))
            .show();
        return false;
    }
    $(validationMessageSelector).hide();
    return true;
}

listenSubmit("#addCMSForm", function () {
    let title = $("#homeTitleId").val();
    // let empty = title.trim().replace(/ \r\n\t/g, '') === '';
    let homePageExperience = $("#homePageExperience").val();
    let shortDescription = $("#homeShortDescription").val();
    let homePageBoxTitle = $("#homePageBoxTitle").val();
    let homePageBoxDes = $("#homePageBoxDes").val();
    let homeDoctorTextId = $("#homeDoctorTextId").val();
    let homeDoctorTitleId = $("#homeDoctorTitleId").val();
    let homeDoctorDescription = $("#homeDoctorDescription").val();
    let homePageCerBoxTl = $("#homePageCerBoxTl").val();
    let homePageCerBoxDes = $("#homePageCerBoxDes").val();
    let homePageStep1Tl = $("#homePageStep1Tl").val();
    let homePageStep1Des = $("#homePageStep1Des").val();
    let homePageStep2Tl = $("#homePageStep2Tl").val();
    let homePageStep2Des = $("#homePageStep2Des").val();
    let homePageStep3Tl = $("#homePageStep3Tl").val();
    let homePageStep3Des = $("#homePageStep3Des").val();
    let homePageStep4Tl = $("#homePageStep4Tl").val();
    let homePageStep4Des = $("#homePageStep4Des").val();

    if (isEmpty($.trim(homePageExperience))) {
        displayErrorMessage(Lang.get("js.home_page_experience_required"));
        return false;
    }
    if (isEmpty($.trim(title))) {
        displayErrorMessage(Lang.get("js.home_page_title_required"));
        return false;
    }
    if (isEmpty($.trim(shortDescription))) {
        displayErrorMessage(Lang.get("js.home_page_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageBoxTitle))) {
        displayErrorMessage(Lang.get("js.home_page_box_required"));
        return false;
    }
    if (isEmpty($.trim(homePageBoxDes))) {
        displayErrorMessage(Lang.get("js.home_page_box_descriptions_required"));
        return false;
    }
    if (isEmpty($.trim(homeDoctorTextId))) {
        displayErrorMessage(Lang.get("js.home_page_certified_required"));
        return false;
    }
    if (isEmpty($.trim(homeDoctorTitleId))) {
        displayErrorMessage(Lang.get("js.home_page_certified_title_required"));
        return false;
    }
    if (isEmpty($.trim(homeDoctorDescription))) {
        displayErrorMessage(Lang.get("js.home_page_certified_doctor_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageCerBoxTl))) {
        displayErrorMessage(Lang.get("js.home_page_certified_box_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageCerBoxDes))) {
        displayErrorMessage(Lang.get("js.home_page_certified_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep1Tl))) {
        displayErrorMessage(Lang.get("js.home_page_step_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep1Des))) {
        displayErrorMessage(Lang.get("js.home_page_step_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep2Tl))) {
        displayErrorMessage(Lang.get("js.home_page_step_two_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep2Des))) {
        displayErrorMessage(Lang.get("js.home_page_step_two_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep3Tl))) {
        displayErrorMessage(Lang.get("js.home_page_step_three_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep3Des))) {
        displayErrorMessage(Lang.get("js.home_page_step_three_description_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep4Tl))) {
        displayErrorMessage(Lang.get("js.home_page_step_four_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep4Tl))) {
        displayErrorMessage(Lang.get("js.home_page_step_four_title_required"));
        return false;
    }
    if (isEmpty($.trim(homePageStep4Des))) {
        displayErrorMessage(Lang.get("js.home_page_step_four_description_required"));
        return false;
    }

    // if (empty) {
    //     displayErrorMessage(
    //         'Home Page Title field is not contain only white space');
    //     return false;
    // }
});

listenSubmit("#createAboutUs", function () {
    let aboutUsTitle = $("#aboutUsTitle").val();
    let aboutUsDes = $("#aboutUsDes").val();
    let aboutUsMission = $("#aboutUsMission").val();

    if (isEmpty($.trim(aboutUsTitle))) {
        displayErrorMessage(
            Lang.get("js.about_us_title_required")
        );
        return false;
    }
    if (isEmpty($.trim(aboutUsDes))) {
        displayErrorMessage(
            Lang.get("js.about_us_description_required")
        );
        return false;
    }
    if (isEmpty($.trim(aboutUsMission))) {
        displayErrorMessage(
            Lang.get("js.about_us_mission_required")
        );
        return false;
    }
});
