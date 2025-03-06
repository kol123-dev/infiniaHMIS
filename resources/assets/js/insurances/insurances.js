Livewire.hook("element.init", ({component}) => {
    if(component.name == 'insurance-table'){
        $('#insurance_filter_status').select2({
            width: "100%",
        });
    }
});

listenClick('.deleteInsuranceBtn', function (event) {
    let insuranceId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexInsuranceUrl').val() + '/' + insuranceId, '', $('#Insurance').val());
});

listenChange('.insuranceStatus', function (event) {
    let insuranceId = $(event.currentTarget).attr('data-id');
    updateInsuranceStatus(insuranceId);
});
listenClick('#insuranceResetFilter', function () {
    $('#filter_status').val(2).trigger('change');
});

window.updateInsuranceStatus = function (id) {
    $.ajax({
        url: $('#indexInsuranceUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refresh')
            }
        },
    });
};


listenChange('#insurance_filter_status', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
});
listenClick('#insuranceResetFilter', function () {
    $('#insurance_filter_status').val(0).trigger('change');
    hideDropdownManually($('#insuranceFilterBtn'), $('.dropdown-menu'));
});
