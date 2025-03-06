window.addEventListener("DOMContentLoaded", loadAddCustomFieldData);

function loadAddCustomFieldData() {

    const moduleName = $("#module_name");
    const fieldType = $("#field_type");
    const editModuleName = $("#edit_module_name");
    const editFieldType = $("#edit_field_type");

    if (moduleName.length) {
        $("#module_name").select2({
            dropdownParent: $("#add_custom_field_modal"),
        });
    }
    if (fieldType.length) {
        $("#field_type").select2({
            dropdownParent: $("#add_custom_field_modal"),
        });
    }
    if (editModuleName.length) {
        $("#edit_module_name").select2({
            dropdownParent: $("#edit_custom_field_modal"),
        });
    }
    if (editFieldType.length) {
        $("#edit_field_type").select2({
            dropdownParent: $("#edit_custom_field_modal"),
        });
    }
}


listenHiddenBsModal("#add_custom_field_modal", function () {
    resetModalForm("#addCustomFieldForm", "#addCustomFieldErrorsBox");
    $("#module_name").val("").trigger("change.select2").select2('close');
    $("#field_type").val("").trigger("change.select2").select2('close');
});

listenChange($('#field_type'), function () {
    var fieldType = $('#field_type').val();
    if (fieldType == 4 || fieldType == 5) {
        $('.comma').removeClass('d-none');
        $('.field-value').removeClass('d-none');
        $('#values').attr('required', true);
    } else {
        $('.comma').addClass('d-none');
        $('.field-value').addClass('d-none');
        $('#values').removeAttr('required');
    }
})

listenSubmit("#addCustomFieldForm", function (event) {
    event.preventDefault();
    var values = $('#values').val().replace(/[;\-!@£$%^&*()_={}<>,]+/g, ' ');
    var field_type = $('#field_type').val();
    if(values == '' && (field_type == 4 || field_type == 5) ){
        displayErrorMessage(Lang.get('js.value_must_be_greter_then'))
    }else{
        $('#values').val(replaceSpacesWithCommas($('#values').val()));
        var data = $(this).serialize();
        $.ajax({
            url: route("add-custom-fields.store"),
            type: "POST",
            data: data,
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message);
                    $("#addCustomFieldForm")[0].reset();
                    $("#add_custom_field_modal").modal("hide");
                    Livewire.dispatch("refresh");
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    }

});

listenHiddenBsModal("#add_custom_field_modal", function () {
    resetModalForm("#addCustomFieldForm", "#addCustomFieldErrorsBox");
    $("#module_name").val("").trigger("change.select2");
    $("#field_type").val("").trigger("change.select2");
});

listenClick("#editCustomFieldBtn", function () {
    let id = $(this).attr("data-id");

    $.ajax({
        url: $("#indexAddCustomFieldURL").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            var data = result.data;
            if (result.success) {
                $('#editFieldId').val(data.id)
                $('#edit_module_name').val(data.module_name).trigger("change.select2")
                $('#edit_field_type').val(data.field_type.toUpperCase()).trigger("change.select2")
                $('#edit_field_name').val(data.field_name)
                $('#edit_grid').val(data.grid)
                $('#edit_values').val(data.values)
                if (data.is_required == 0) {
                    $('#edit_is_reqired').val(0).prop('checked', false);
                } else {
                    $('#edit_is_reqired').val(1).prop('checked', true);
                }
                if (data.field_type == 4 || data.field_type == 5) {
                    $('.edit_comma').removeClass('d-none');
                    $('.edit-field-value').removeClass('d-none');
                    $('#edit_values').attr('required', true)
                } else {
                    $('.edit_comma').addClass('d-none');
                    $('.edit-field-value').addClass('d-none');
                    $('#edit_values').removeAttr('required');
                }
            }
        },
    });
});


listenChange($('#edit_field_type'), function () {
    var fieldType = $('#edit_field_type').val();
    if (fieldType == 4 || fieldType == 5) {
        $('.edit_comma').removeClass('d-none');
        $('.edit-field-value').removeClass('d-none');
        $('#edit_values').attr('required', true)
    } else {
        $('.edit_comma').addClass('d-none');
        $('.edit-field-value').addClass('d-none');
        $('#edit_values').removeAttr('required');
    }
})

listenSubmit('#editCustomFieldForm', function (e) {
    e.preventDefault()
    var edit_values = $('#edit_values').val().replace(/[;\-!@£$%^&*()_={}<>,]+/g, ' ');
    var edit_field_type = $('#edit_field_type').val();
    if(edit_values.split(' ').length == 1 && (edit_field_type == 4 || edit_field_type == 5) ){
        displayErrorMessage(Lang.get('js.value_must_be_greter_then'));
    }else{
        $('#edit_values').val(replaceSpacesWithCommas($('#edit_values').val()));
        var id = $('#editFieldId').val();
        $.ajax({
            url: $('#indexAddCustomFieldURL').val() + '/' + id,
            type: 'put',
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    displaySuccessMessage(result.message)
                    $('#edit_custom_field_modal').modal('hide')
                    Livewire.dispatch('refresh')
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message)
            },
        });
    }

})

listenHiddenBsModal('#edit_custom_field_modal', function () {
    resetModalForm('#editCustomFieldForm');
    $("#edit_module_name").val("").select2('close').trigger("change.select2");
    $("#edit_field_type").val("").select2('close').trigger("change.select2");
});

listenClick('.custom-field-delete-btn', function (event) {
    let fieldId = $(event.currentTarget).attr('data-id');
    deleteItem($('#indexAddCustomFieldURL').val() + '/' + fieldId, '',
        $('#customField').val());
});

function replaceSpacesWithCommas(value) {
    // var result = value.replace(/[ ;\-!@£$%^&*()_={}<>]+/g, ',');
    var result = value.replace(/[^a-zA-Z0-9]+/g, ',');
    result = result.replace(/,+$/, '');
    result = result.trim();
    return result;
}
