document.addEventListener('DOMContentLoaded', loadEmployeeDoctorData)

function loadEmployeeDoctorData() {
    if (!$('#invoice_status_filter').length) {
        return
    }
    $('#invoice_status_filter').select2({
        width: '100%',
    });
}
listenChange('#invoice_status_filter', function () {
    Livewire.dispatch('changeFilter', {statusFilter: $(this).val()})
});

listen('click', '#resetEmployeeInvoiceFilter', function () {
    $('#invoice_status_filter').val(0).trigger('change');
});
