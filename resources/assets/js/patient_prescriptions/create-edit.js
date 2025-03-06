document.addEventListener('DOMContentLoaded', loadPaymentPrescriptionData)

function loadPaymentPrescriptionData() {
    if (!$('#indexPatientPrescriptionId').length) {
        return
    }

    $('#patient_id,#filter_status').select2({
        width: '100%',
    });
}
