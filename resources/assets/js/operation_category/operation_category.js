'use strict';

listenSubmit('#addOperationCatForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#operationCatSave');
    loadingButton.button('loading');
    $.ajax({
        url: $('#operationCategoryCreateUrl').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#add_operation_categories_modal').modal('hide');
                Livewire.dispatch('refresh')
            }
        },
        error: function (result) {
            printErrorMessage('#operationCatErrorsBox', result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenClick('.operation-category-delete-btn', function (event) {
    let operationCategoryId = $(event.currentTarget).attr('data-id');
    deleteItem($('#operationCategoryUrl').val() + '/' + operationCategoryId, '', $('#operationCategory').val());
});

listenClick('.operation-category-edit-btn', function (event) {
    let operationCategoryId = $(event.currentTarget).attr('data-id');
    renderOperationCategoryData(operationCategoryId);
});

function renderOperationCategoryData(id) {
    $.ajax({
        url: $('#operationCategoryUrl').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#editOperationCategoryIdText').val(result.data.id);
                $('#editOperationCatName').val(result.data.name);
                $('#edit_operation_categories_modal').modal('show');
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenSubmit('#editOperationCatForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editOperationCatSave');
    loadingButton.button('loading');
    var id = $('#editOperationCategoryIdText').val();
    $.ajax({
        url: $('#operationCategoryUrl').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                $('#edit_operation_categories_modal').modal('hide')
                if ($('#operationCategoryShowUrl').length) {
                    window.location.href = $('#operationCategoryShowUrl').val()
                } else {
                    Livewire.dispatch('refresh')
                }

            }
        },
        error: function (result) {
            UnprocessableInputError(result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#add_operation_categories_modal', function () {
    resetModalForm('#addOperationCatForm', '#operationCatErrorsBox');
    $('#operationCategoryId').val('').trigger('change.select2');
});

listenHiddenBsModal('#edit_operation_categories_modal', function () {
    resetModalForm('#editOperationCatForm', '#editOperationCatErrorsBox');
});
