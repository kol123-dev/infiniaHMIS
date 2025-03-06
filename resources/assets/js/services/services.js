'use strict';

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'service-table'){
        $('#service_filter_status').select2({
            width:"100%",
        });
    }
});

function updateServiceStatus(id) {
    $.ajax({
        url: $('#showServiceReportUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
            }
        },
    });
}

listenClick('.delete-service-btn', function (event) {
    let serviceId = $(event.currentTarget).attr('data-id');
    deleteItem(
        $('#showServiceReportUrl').val() + '/' + serviceId,
        '',
        $('#Service').val(),
    );
});

listenChange('.serviceStatus', function (event) {
    let serviceId = $(event.currentTarget).attr('data-id');
    updateServiceStatus(serviceId);
});

listenChange('#service_filter_status', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
    hideDropdownManually($('#incomeFilterBtn'), $('#incomeFilter'));
});

listenClick('#serviceResetFilter', function () {
    $('#service_filter_status').val(0).trigger('change');
    hideDropdownManually($('#serviceFilterBtn'), $('.dropdown-menu'));
});
