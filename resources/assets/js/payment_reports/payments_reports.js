// document.addEventListener('DOMContentLoaded', loadPaymentReportData)

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'payment-report'){
        loadPaymentReportData();

        $('#filterPaymentReport').select2({
            width: "100%",
        });
    }
});

function loadPaymentReportData() {
    if (!$('#filterPaymentAccount').length) {
        return
    }
    $('#filterPaymentAccount').select2({
        width: '100%',
    });
}

listenChange('#filterPaymentReport', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
});

listen('click', '#paymentReportResetFilter', function () {
    $('#filterPaymentReport').val(0).trigger('change');
    hideDropdownManually($('#paymentReportFilterBtn'), $('.dropdown-menu'));
});
