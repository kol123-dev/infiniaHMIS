document.addEventListener("DOMContentLoaded", loadOpdPatientData);

function loadOpdPatientData() {
    var customDate = $('#customFieldDate').val();
    var customDateTime = $('#customFieldDateTime').val();

    if (
        !$("#createOpdPatientForm").length &&
        !$("#editOpdPatientDepartmentForm").length
    ) {
        return;
    }

    $(
        "#opdPatientId, #opdDoctorId,#opdPaymentMode,#editOpdPatientId, #editOpdDoctorId,#editOpdPaymentMode"
    ).select2({
        width: "100%",
    });

    $("#opdCaseId ,#editOpdCaseId").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.case"),
    });

    $('#customFieldDate').flatpickr({
        defaultDate: customDate ? customDate : new Date(),
        dateFormat: 'Y-m-d',
        locale: $('.userCurrentLanguage').val(),
    });

    $('#customFieldDateTime').flatpickr({
        enableTime: true,
        defaultDate: customDateTime ? customDateTime : new Date(),
        dateFormat: "Y-m-d H:i",
        locale: $('.userCurrentLanguage').val(),
    });

    let appointmentDateFlatPicker = $(
        "#opdAppointmentDate,#editOpdAppointmentDate "
    ).flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: $(".userCurrentLanguage").val(),
    });

    if ($(".lastVisit").val()) {
        $("#opdPatientId,#editOpdPatientId")
            .val($(".lastVisit").val())
            .trigger("change");
        $("#opdPatientId,#editOpdPatientId").attr("disabled", true);
    }

    if ($(".isEdit").val()) {
        $("#opdPatientId,#editOpdPatientId").attr("disabled", true);
        $("#opdPatientId,#editOpdPatientId").trigger("change");
        appointmentDateFlatPicker.set(
            "minDate",
            $("#opdAppointmentDate,#editOpdAppointmentDate").val()
        );
    } else {
        appointmentDateFlatPicker.setDate(new Date());
        appointmentDateFlatPicker.set("minDate", new Date());
    }
}

listenSubmit(
    "#createOpdPatientForm, #editOpdPatientDepartmentForm",
    function () {
        $("#opdPatientId,#editOpdPatientId").attr("disabled", false);
        $("#btnOpdSave,#btnEditOpdSave").attr("disabled", true);
    }
);

listenChange("#opdPatientId,#editOpdPatientId", function () {
    if ($(this).val() !== "") {
        $.ajax({
            url: $(".opdPatientCasesUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    $("#opdCaseId,#editOpdCaseId").empty();
                    $("#opdCaseId,#editOpdCaseId").removeAttr("disabled");
                    $.each(data.data, function (i, v) {
                        if ($(".patientCaseId").val() == v) {
                            $("#editOpdCaseId").append(
                                $("<option></option>")
                                    .attr("value", i)
                                    .attr("selected", true)
                                    .text(v)
                            );
                        } else {
                            $("#opdCaseId,#editOpdCaseId").append(
                                $("<option></option>").attr("value", i).text(v)
                            );
                        }
                    });
                } else {
                    $("#opdCaseId,#editOpdCaseId").prop("disabled", true);
                }
            },
        });
    }
    $("#opdCaseId,#editOpdCaseId").empty();
    $("#opdCaseId,#editOpdCaseId").prop("disabled", true);

    $("#opdCaseId ,#editOpdCaseId").select2({
        width: "100%",
        placeholder:
            Lang.get("js.choose") +
            " " +
            Lang.get("js.case"),
    });
});

listenChange("#opdDoctorId,#editOpdDoctorId", function () {
    if ($(this).val() !== "") {
        $.ajax({
            url: $(".doctorOpdChargeUrl").val(),
            type: "get",
            dataType: "json",
            data: { id: $(this).val() },
            success: function (data) {
                if (data.data.length !== 0) {
                    $("#opdStandardCharge,#editOpdStandardCharge").val(
                        data.data[0].standard_charge.toFixed(2)
                    );
                } else {
                    $("#opdStandardCharge,#editOpdStandardCharge").val(0);
                }
            },
        });
    }
});


function validateForm(formSelector, errorsBoxSelector) {
    var isValid = true;
    var form = $(formSelector);

    form.find('.dynamic-field').each(function () {
        var fieldValue = $(this).val();
        var fieldLabel = $(this).closest('.form-group').find('label').text().replace(':', '').trim();

        if ($(this).is(':input[type="text"], :input[type="number"], textarea')) {
            if (!fieldValue || fieldValue.trim() === '') {
                $(errorsBoxSelector).show().removeClass('d-none').html(fieldLabel + ' ' + Lang.get('js.field_required')).delay(5000).slideUp(300);
                isValid = false;
                return false;
            }
        } else if ($(this).is(':input[type="checkbox"]')) {
            if (!$(this).is(':checked')) {
                $(errorsBoxSelector).show().removeClass('d-none').html(fieldLabel + ' ' + Lang.get('js.field_required')).delay(5000).slideUp(300);
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

listenClick('#btnOpdSave', function () {
    validateForm('#createOpdPatientForm', '#createOpdErrorsBox');
});
