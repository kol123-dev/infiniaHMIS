document.addEventListener('DOMContentLoaded', loadPatientAdmissionData)

let patientAdmissionDate = undefined;

function loadPatientAdmissionData() {
    if (!$('#createPatientAdmission').length && !$('#editPatientAdmission').length) {
        return
    }

    $('#admissionPatientId, #admissionDoctorId, #admissionPackageId, #admissionInsuranceId, #admissionBedId').select2({
        width: '100%',
    });
    patientAdmissionDate = $('#admissionDate').flatpickr({
        dateFormat: "Y-m-d H:i",
        sideBySide: true,
        enableTime: true,
        locale: $('.userCurrentLanguage').val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    $('#admissionPatientId').focus();

    if ($('.isEdit').val()) {
        setTimeout(function () {
            $('#admissionDate').trigger('dp.change');
        }, 300);
        let dischargeDate = undefined;
        let patientBirthDate = $('#admissionPatientBirthDate').val();
        $("#admissionDate").flatpickr({
            dateFormat: "Y-m-d H:i",
            sideBySide: true,
            enableTime: true,
            minDate: patientBirthDate,
            locale: $('.userCurrentLanguage').val(),
            position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
            onChange: function (selectedDates, dateStr, instance) {
                let minDate = moment($('#admissionDate').val()).add(1, 'days').format();
                if (typeof dischargeDate != "undefined") {
                    dischargeDate.set('minDate', minDate)
                }
            }
        });

        dischargeDate = $("#admissionDischargeDate").flatpickr({
            dateFormat: "Y-m-d H:i",
            sideBySide: true,
            minDate: minDate,
            useCurrent: false,
            enableTime: true,
            locale: $('.userCurrentLanguage').val(),
            position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
        });
        let minDate = moment($('#admissionDate').val()).add(1, 'days').format();
        dischargeDate.set('minDate', minDate)
    }

}

listenSubmit('#createPatientAdmission, #editPatientAdmission', function () {
    if ($('.error-msg').text() !== '') {
        $('.phoneNumber').focus();
        return false;
    }
});
listenChange('#admissionPatientId', function (event) {
    let id = $(this).val();
    getPatientAdmissionDate(id);
});
window.getPatientAdmissionDate = function (id) {
    $.ajax({
        url: $('#admissionPatientBirthUrl').val() + '/' + id,
        method: 'get',
        cache: false,
        success: function (result) {
            patientAdmissionDate.set('minDate', result.user.dob)
        },
    });
};
