Livewire.hook("element.init", ({component}) => {
    if(component.name == 'pharmacist-table'){
        $('#pharmacist_filter_status').select2({
            width: '100%',
        });
    }
});

listen('click', '.delete-pharmacist-btn', function (event) {
    let pharmacistId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexPharmacistUrl').val() + '/' + pharmacistId, '',
        $('#Pharmacist').val());
});

listen('change', '.pharmacistStatus', function (event) {
    let pharmacistId = $(event.currentTarget).attr('data-id');
    updatePharmacistsStatus(pharmacistId);
});

listen('click', '#pharmacistResetFilter', function () {
    $('#pharmacist_filter_status').val(0).trigger('change');
    hideDropdownManually($('#pharmacistFilterBtn'), $('.dropdown-menu'));
});

listenChange('#pharmacist_filter_status', function () {
    Livewire.dispatch('changeFilter', { statusFilter : $(this).val() })
});

window.updatePharmacistsStatus = function (id) {
    $.ajax({
        url: $('#indexPharmacistUrl').val() + '/' + +id + '/active-deactive',
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
