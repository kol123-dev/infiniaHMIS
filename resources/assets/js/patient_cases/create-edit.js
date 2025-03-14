document.addEventListener('DOMContentLoaded', loadPatientCaseData)

function loadPatientCaseData() {
    if (!$('#createPatientCaseForm').length && !$('#editPatientCaseForm').length) {
        return
    }

    $('#casePatientId, #caseDoctorId').select2({
        width: '100%',
    });
    $('#caseDate').flatpickr({
        enableTime: true,
        // defaultDate: new Date(),
        dateFormat: "Y-m-d H:i",
        locale: $('.userCurrentLanguage').val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });
    // $('#casePatientId').focus();

    $('.price-input').trigger('input');

}
listenSubmit('#createPatientCaseForm, #editPatientCaseForm', function () {
    if ($('.error-msg').text() !== '') {
        $('.phoneNumber').focus();
        return false;
    }
});
