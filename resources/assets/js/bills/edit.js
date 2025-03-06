'use strict';

document.addEventListener('DOMContentLoaded', loadBillEdit)

function loadBillEdit () {

    if (!$('#editBillPatientAdmissionId').length) {
        return false;
    }

    setTimeout(function () {
        $('#patientAdmissionId').val($('#editBillPatientAdmissionId').val());
        $('#patientAdmissionId').trigger('change');
    }, 500);

}
