'use strict';

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'patient-prescription-table'){
        $('#patients_prescription_filter_status').select2({
            width: "100%",
        });
    }
});

listenChange('#patients_prescription_filter_status', function () {
    Livewire.dispatch('changeFilter',{statusFilter : $(this).val()})
});
listenClick('#patientPrescriptionResetFilter', function () {
    $('#patients_prescription_filter_status').val(2).trigger('change');
    hideDropdownManually($('#patientsPrescriptionFilterBtn'), $('.dropdown-menu'));
});
