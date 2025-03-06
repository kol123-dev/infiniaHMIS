document.addEventListener('DOMContentLoaded', loadEditEmployeePayrollData)

function loadEditEmployeePayrollData() {
    if (!$('#type').length) {
        return
    }
    setTimeout(function () {
        $('#type').trigger('change');
    }, 1000);
}
