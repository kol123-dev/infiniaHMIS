// document.addEventListener('DOMContentLoaded', loadAdminInvoiceData)

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'invoice-table'){
        loadAdminInvoiceData()

        $('#invoice_status_filter').select2({
            width: "100%",
        });
    }
});

function loadAdminInvoiceData()
{
    listen('click', '#resetEmployeeInvoiceFilter', function () {
        $('#invoice_status_filter').val(2).trigger('change');
        hideDropdownManually($('#invoiceFilterBtn'), $('.dropdown-menu'));
    });
}

listen('click', '.deleteInvoicesBtn', function (event) {
    let id = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexInvoiceUrl').val() + '/' + id, '', $('#Invoices').val());
});

listenChange('#invoice_status_filter', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
});
