
listenChange('#doctorsHead', function () {
    Livewire.dispatch('changeFilter',  {statusFilter : $(this).val()})
});
listenClick('#doctorResetFilter', function () {
    $('#doctorsHead').val(2).trigger('change');
    hideDropdownManually($('#doctorsFilterBtn'), $('.dropdown-menu'));
});
