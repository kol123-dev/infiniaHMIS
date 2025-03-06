'use strict'

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'case-handler-table'){
        $('#caseHandlerHead').select2({
            width: "100%",
        });
    }
});

listenClick('.delete-btn', function (event) {
        let caseHandlerId = $(event.currentTarget).attr('data-id');
        deleteItem($('#indexCaseHandlerUrl').val() + '/' + caseHandlerId, '',
            $('#caseHandler').val());
});

listenChange('.case-handler-status', function (event) {
        let caseHandlerId = $(event.currentTarget).attr('data-id');
    updateCaseHandlerStatus(caseHandlerId);
});

listenClick('#caseHandlerResetFilter', function () {
    $('#caseHandlerHead').val(2).trigger('change');
    hideDropdownManually($('#caseHandlerFilterBtn'), $('.dropdown-menu'));
});

function updateCaseHandlerStatus(id) {
    $.ajax({
        url: $('#indexCaseHandlerUrl').val() + '/' + id + '/active-deactive',
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch('refresh');
            }
        },
    });
}

listenChange('#caseHandlerHead', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
    hideDropdownManually($('#caseHandlerFilterBtn'), $('#caseHandlerFilter'));
});
