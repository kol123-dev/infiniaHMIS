document.addEventListener("DOMContentLoaded", loadGeneratePatientIdCardData);

function loadGeneratePatientIdCardData() {
    Lang.setLocale($(".userCurrentLanguage").val());

    const patientTemplateID = $("#templateId");
    const smartCardPatientId = $("#PatientId");

    if (patientTemplateID.length) {
        $("#templateId").select2({
            width: "100%",
            dropdownParent: $("#generate_patient_card_modal"),
        });
    }

    if (smartCardPatientId.length) {
        $("#PatientId").select2({
            width: "100%",
            dropdownParent: $("#generate_patient_card_modal"),
        });
    }
}

listenClick("#OnlyOnePatient", function () {
    $(".patient_select").removeClass("d-none");
});

listenClick("#AllPatient", function () {
    $(".patient_select").addClass("d-none");
});

listenClick("#RemainingPatient", function () {
    $(".patient_select").addClass("d-none");
});

listenHiddenBsModal("#generate_patient_card_modal", function () {
    resetModalForm("#addTemplateForm", "#AddTemplateErrorsBox");
    $(".select_template_id").trigger("change");
    $(".select_patient_id").trigger("change");
    $(".patient_select").addClass("d-none");
});

listenSubmit("#addTemplateForm", function (e) {
    e.preventDefault();

    let OnePatient = $("#OnlyOnePatient").prop("checked");
    let patientId = $("#PatientId").val();

    if (OnePatient && patientId == "") {
        displayErrorMessage(Lang.get("js.patient_required"));
        return false;
    }

    let loadingButton = jQuery(this).find("#AddTemplateSave");
    loadingButton.button("loading");
    let data = {
        formSelector: $(this),
        url: route("generate-patient-smart-cards.store"),
        type: "POST",
    };

    newRecord(data, loadingButton, "#generate_patient_card_modal");
});

listenClick(".generate-patient-card-delete-btn", function (event) {
    let patientIdCardTemplateId = $(event.currentTarget).data("id");
    deleteItem(
        route("generate-patient-smart-cards.destroy", patientIdCardTemplateId),
        " ",
        Lang.get("js.patient_id_card_template")
    );
});

listenClick(".ShowPatientCardData", function () {
    let id = $(this).data("id");

    $.ajax({
        url: route("generate-patient-smart-cards.show", id),
        type: "GET",
        success: function (data) {
            if (data.success) {
                function toggleElement(element, condition) {
                    if (condition === false) {
                        element.addClass("d-none");
                    } else {
                        element.removeClass("d-none");
                    }
                }

                $("#card_profilePicture").attr(
                    "src",
                    data.data.patient_user.image_url
                );
                $(".card_name").text(data.data.patient_user.full_name);
                $(".smart-card-header").css(
                    "background-color",
                    data.data.id_card_template.color
                );
                $(".download-icon").css("color",data.data.id_card_template.color);
                toggleElement(
                    $("#ShowCardEmail"),
                    data.data.id_card_template.email
                );
                toggleElement(
                    $("#ShowCardPhone"),
                    data.data.id_card_template.phone
                );
                toggleElement(
                    $("#ShowCardBloodGroup"),
                    data.data.id_card_template.blood_group
                );
                toggleElement(
                    $("#ShowCardAddress"),
                    data.data.id_card_template.address
                );
                toggleElement(
                    $("#ShowPatientUniqueId"),
                    data.data.id_card_template.patient_unique_id
                );
                toggleElement(
                    $("#ShowCardDob"),
                    data.data.id_card_template.dob
                );

                if (data.data.id_card_template.email !== false) {
                    $(".patient_email").text(data.data.patient_user.email);
                }
                if (data.data.id_card_template.phone !== false) {
                    $(".patient_contact").text(data.data.patient_user.phone);
                }
                if (data.data.id_card_template.blood_group !== false) {
                    $(".blood_group").text(data.data.patient_user.blood_group);
                }
                if (data.data.id_card_template.address !== false) {
                    if (data.data.address !== null) {
                        $(".card_address").text(
                            data.data.address.address1 ??
                                "" + " " + data.data.address.address2 ??
                                ""
                        );
                    }
                }
                if (data.data.id_card_template.patient_unique_id !== false) {
                    $(".patient_unique_id").text(data.data.patient_unique_id);
                }
                if (data.data.id_card_template.dob !== false) {
                    $(".patient_dob").text(data.data.patient_user.dob);
                }

                if (data.data.patient_user.dob == null) {
                    $("#ShowCardDob").addClass("d-none");
                }

                if (data.data.patient_user.email == null) {
                    $("#ShowCardEmail").addClass("d-none");
                }

                if (data.data.patient_user.phone == null) {
                    $("#ShowCardPhone").addClass("d-none");
                }

                if (data.data.patient_user.blood_group == null) {
                    $("#ShowCardBloodGroup").addClass("d-none");
                }

                if (data.data.patient_unique_id == null) {
                    $("#ShowPatientUniqueId").addClass("d-none");
                }
                if (data.data.address == null) {
                    $("#ShowCardAddress").addClass("d-none");
                }
            }
        },
    });
});

listenClick(".ShowPatientCardData", function () {
    let id = $(this).data("id");

    $.ajax({
        url: route("generate.qrcode", id),
        type: "GET",
        success: function (data) {
            $(".svgContainer").html(data);
        },
    });
});
