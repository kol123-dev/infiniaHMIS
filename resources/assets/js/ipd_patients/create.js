document.addEventListener("DOMContentLoaded", loadIpdPatientCreate);

function loadIpdPatientCreate() {

    var customDate = $('#customFieldDate').val();
    var customDateTime = $('#customFieldDateTime').val();

    if (!$("#ipdAdmissionDate").length && !$("#editIpdAdmissionDate").length) {
        return;
    }

    $(
        "#ipdPatientId, #ipdDoctorId, #ipdBedTypeId,#editIpdPatientId, #editIpdDoctorId, #editIpdBedTypeId"
    ).select2({
        width: "100%",
    });

    $("#ipdCaseId, #editIpdCaseId ").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.case_id"),
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

    $("#ipdBedId, #editIpdBedId").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.bed"),
    });

    let admissionFlatPicker = $(
        "#ipdAdmissionDate, #editIpdAdmissionDate"
    ).flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: $(".userCurrentLanguage").val(),
    });

    if ($(".isEdit").val()) {
        $(".ipdPatientId, .ipdBedTypeId").trigger("change");
        admissionFlatPicker.set("minDate", $(".ipdAdmissionDate").val());
    } else {
        admissionFlatPicker.setDate(new Date());
        admissionFlatPicker.set("minDate", new Date());
    }
}

listenClick(".ipd-diagnosis-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href="#cases-tab"]').tab("show");
    $(this).removeClass("active");
    $("#cases-tab").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $("#cases-tab").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenClick(".ipd-charges-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href=".ipdCharges"]').tab("show");
    $(this).removeClass("active");
    $(".ipdCharges").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $(".ipdCharges").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenClick(".ipd-consultant-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href=".ipdConsultantInstruction"]').tab("show");
    $(this).removeClass("active");
    $(".ipdConsultantInstruction").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $(".ipdConsultantInstruction").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenClick(".ipd-prescription-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href=".ipdPrescriptions"]').tab("show");
    $(this).removeClass("active");
    $(".ipdPrescriptions").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $(".ipdPrescriptions").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenClick(".ipd-payment-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href=".ipdPayment"]').tab("show");
    $(this).removeClass("active");
    $(".ipdPayment").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $(".ipdPayment").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenClick(".ipd-operation-btn", function (event) {
    event.preventDefault();
    $('#myTab a[href=".ipdOperation"]').tab("show");
    $(this).removeClass("active");
    $(".ipdOperation").attr("aria-selected", true);
    $("#ipdOverview").attr("aria-selected", false);
    $(".ipdOperation").addClass("active");
    $("#ipdOverview").removeClass("active");
});

listenKeyup(".ipdDepartmentFloatNumber", function () {
    if ($(this).val().indexOf(".") != -1) {
        if ($(this).val().split(".")[1].length > 2) {
            if (isNaN(parseFloat(this.value))) return;
            this.value = parseFloat(this.value).toFixed(2);
        }
    }
    return this;
});

listenSubmit(
    "#createIpdPatientForm, #editIpdPatientDepartmentForm",
    function () {
        $("#ipdSave, #btnIpdPatientEdit").attr("disabled", true);
    }
);

listenChange(".ipdPatientId", function () {
    if ($(this).val() !== "") {
        $.ajax({
            url: $(".patientCasesUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    $("#ipdDepartmentCaseId,#editIpdDepartmentCaseId").empty();
                    $(
                        "#ipdDepartmentCaseId,#editIpdDepartmentCaseId"
                    ).removeAttr("disabled");
                    $.each(data.data, function (i, v) {
                        $(
                            "#ipdDepartmentCaseId,#editIpdDepartmentCaseId"
                        ).append(
                            $("<option></option>").attr("value", i).text(v)
                        );
                    });
                    $("#ipdDepartmentCaseId,#editIpdDepartmentCaseId")
                        .val($("#editIpdPatientCaseId").val())
                        .trigger("change.select2");
                } else {
                    $("#ipdDepartmentCaseId,#editIpdDepartmentCaseId").prop(
                        "disabled",
                        true
                    );
                }
            },
        });
    }
    $("#ipdDepartmentCaseId,#editIpdDepartmentCaseId").empty();
    $("#ipdDepartmentCaseId,#editIpdDepartmentCaseId").prop("disabled", true);

    $("#ipdDepartmentCaseId, #editIpdDepartmentCaseId ").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.case_id"),
    });
});

listenChange(".ipdBedTypeId", function () {
    let bedId = null;
    let bedTypeId = null;
    if (
        typeof $("#editIpdPatientBedId").val() != "undefined" &&
        typeof $("#editIpdPatientBedTypeId").val() != "undefined"
    ) {
        bedId = $("#editIpdPatientBedId").val();
        bedTypeId = $("#editIpdPatientBedTypeId").val();
    }

    if ($(this).val() !== "") {
        let bedType = $(this).val();
        $.ajax({
            url: $(".patientBedsUrl").val(),
            type: "get",
            dataType: "json",
            data: {
                id: $(this).val(),
                isEdit: $(".isEdit").val(),
                bedId: bedId,
                ipdPatientBedTypeId: bedTypeId,
            },
            success: function (data) {
                if (data.data !== null) {
                    if (data.data.length !== 0) {
                        $("#ipdBedId,#editIpdBedId").empty();
                        $("#ipdBedId,#editIpdBedId").removeAttr("disabled");
                        $.each(data.data, function (i, v) {
                            $("#ipdBedId,#editIpdBedId").append(
                                $("<option></option>").attr("value", i).text(v)
                            );
                        });
                        if (
                            typeof $("#editIpdPatientBedId").val() !=
                            "undefined" &&
                            typeof $("#editIpdPatientBedTypeId").val() !=
                            "undefined"
                        ) {
                            if (
                                bedType === $("#editIpdPatientBedTypeId").val()
                            ) {
                                $("#ipdBedId,#editIpdBedId")
                                    .val($("#editIpdPatientBedId").val())
                                    .trigger("change.select2");
                            }
                        }
                        $("#ipdBedId,#editIpdBedId").prop("required", true);
                    }
                } else {
                    $("#ipdBedId,#editIpdBedId").prop("disabled", true);
                }
            },
        });
    }
    $("#ipdBedId,#editIpdBedId").empty();
    $("#ipdBedId,#editIpdBedId").prop("disabled", true);

    $("#ipdBedId, #editIpdBedId").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.bed"),
    });
});


function validateForm(formSelector, errorsBoxSelector) {
    var isValid = true;
    var form = $(formSelector);

    form.find('.dynamic-field').each(function () {
        var fieldValue = $(this).val();
        var fieldLabel = $(this).closest('.form-group').find('label').text().replace(':', '').trim();

        if ($(this).is(':input[type="text"], :input[type="number"], textarea')) {
            if (!fieldValue || fieldValue.trim() === '') {
                $(errorsBoxSelector).show().removeClass('d-none').html(fieldLabel + Lang.get('js.field_required')).delay(5000).slideUp(300);
                isValid = false;
                return false;
            }
        } else if ($(this).is(':input[type="checkbox"]')) {
            if (!$(this).is(':checked')) {
                $(errorsBoxSelector).show().removeClass('d-none').html(fieldLabel + Lang.get('js.field_required')).delay(5000).slideUp(300);
                isValid = false;
                return false;
            }
        } else if ($(this).is('select')) {
            if (!fieldValue && $(this).val().length === 0 && fieldValue.trim() === '') {
                $(errorsBoxSelector).show().removeClass('d-none').html('Please select ' + fieldLabel).delay(5000).slideUp(300);
                isValid = false;
                return false;
            }
        }
    });

    event.preventDefault();

    if (isValid) {
        form.submit();
    }
}

listenClick('#ipdSave', function () {
    validateForm('#createIpdPatientForm', '#createIpdErrorsBox');
});

listenClick('#btnIpdPatientEdit', function () {
    validateForm('#editIpdPatientDepartmentForm', '#editIpdErrorsBox');
});
