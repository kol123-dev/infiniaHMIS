Livewire.hook("element.init", ({component}) => {
    if(component.name == 'patient-table'){
        $('#patient_filter_status').select2({
            width: "100%",
        });
    }
});

listen('click', '.delete-patient-btn', function (event) {
    let patientId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexPatientUrl').val() + '/' + patientId, '', $('#Patients').val());
});

listenChange('.patientStatus', function (event) {
    let patientId = $(event.currentTarget).attr('data-id');
    updatePatientStatus(patientId);
});

window.updatePatientStatus = function (id) {
    $.ajax({
        url: $('#indexPatientUrl').val() + '/' + +id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refresh');
            }
        },
    });
};

listenChange('#patient_filter_status', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
});

listenClick('#patientResetFilter', function () {
    $('#patient_filter_status').val(0).trigger('change');
    hideDropdownManually($('#patientFilterBtn'), $('.dropdown-menu'));
});
