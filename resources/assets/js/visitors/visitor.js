// document.addEventListener('DOMContentLoaded', loadVisitorData)

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'visitor-table'){
        loadVisitorData();
    }
});

function loadVisitorData() {
    $('#visitorsHead').select2({
        width: '100%',
    });

    if (!$('#purposeArr').length) {
        return
    }

    $('#purposeArr').select2({
        width: '100%',
    });
}

listenClick('.delete-visitor-btn', function (event) {
    let visitorId = $(event.currentTarget).attr('data-id');
    deleteItem($('.visitorUrl').val() + '/' + visitorId, '', $('#Visitor').val());
});

listenClick('#visitorResetFilter', function () {
    $('#visitorsHead').val(0).trigger('change');
    hideDropdownManually($('#visitorsFilterBtn'), $('.dropdown-menu'));
});
listenChange('#visitorsHead', function () {
    Livewire.dispatch('changeFilter', {statusFilter : $(this).val()})
});
