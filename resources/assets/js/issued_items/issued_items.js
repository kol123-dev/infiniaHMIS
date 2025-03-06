Livewire.hook("element.init", ({component}) => {
    if(component.name == 'issued-item-table'){
        $('#issuedItemHead').select2({
            width: "100%",
        });
    }
});

$('#filter_status').select2()

listenClick('#issuedItemResetFilter', function () {
    $('#issuedItemHead').val(2).trigger('change')
})

listenClick('.deleteIssuedItemBtn', function (event) {
    let issuedItemId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexIssuedItemUrl').val() + '/' + issuedItemId,
        '',
        $('#issuedItem').val())
})

listenClick('.changes-status-btn', function (event) {
    let issuedItemId = $(this).attr('data-id');
    const issuedItemStatus = $(this).attr('status');
    Lang.setLocale($('.userCurrentLanguage').val())
    if (!issuedItemStatus) {
        swal({
            title: Lang.get('js.change_status') + '!',
            text: Lang.get('js.are_you_sure_want_to_return_this_item') + '?',
            type: 'warning',
            icon: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonColor: '#50cd89',
            showLoaderOnConfirm: true,
            buttons: {
                confirm: Lang.get('js.yes'),
                cancel: Lang.get('js.no'),
            },
        }).then(function (result) {
            if (result) {
                $.ajax({
                    url: $('#indexReturnIssuedItemUrl').val(),
                    type: 'get',
                    dataType: 'json',
                    data: { id: issuedItemId },
                    success: function (data) {
                        swal({
                            title: Lang.get('js.item_returned'),
                            icon: 'success',
                            confirmButtonColor: '#50cd89',
                            timer: 2000,
                        })
                        Livewire.dispatch('refresh')
                    },
                })
            }
        })
    }
})
listenChange('#issuedItemHead', function () {
    Livewire.dispatch('changeFilter',{statusFilter : $(this).val()})
    hideDropdownManually($('#issuedItemFilter'), $('#issuedItemFilter'))
})
