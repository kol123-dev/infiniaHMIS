'use strict';
listenSubmit('#addPathologyUnitForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#pathologyUnitSave');
    loadingButton.button('loading');
    $('#pathologyUnitSave').attr('disabled', true);
    $.ajax({
        url: $('#createPathologyUnitURL').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addPathologyUnitsModal').modal('hide');
                Livewire.dispatch('refresh')
                $('#pathologyUnitSave').attr('disabled', false);
            }
        },
        error: function (result) {
            printErrorMessage('#pUniValidationErrorsBox', result);
            $('#pathologyUnitSave').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenSubmit('#editPathologyUnitsForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editPathologyUnitSaveBtn');
    loadingButton.button('loading');
    var id = $('#pathologyUnitId').val();
    $('#editPathologyUnitSaveBtn').attr('disabled', true);
    $.ajax({
        url: $('#pathologyUnitURL').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editPathologyUnitsModal').modal('hide');
                Livewire.dispatch('refresh')
                $('#editPathologyUnitSaveBtn').attr('disabled', false);
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
            $('#editPathologyUnitSaveBtn').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#addPathologyUnitsModal', function () {
    resetModalForm('#addPathologyUnitForm', '#pUniValidationErrorsBox');
    $('#pathologyUnitSave').attr('disabled', false);
});

listenHiddenBsModal('#editPathologyUnitsModal', function () {
    resetModalForm('#editPathologyUnitsForm', '#editPUniValidationErrorsBox');
    $('#editPathologyUnitSaveBtn').attr('disabled', false);
});

window.renderPathologyUnitData = function (id) {
    $.ajax({
        url: $('#pathologyUnitURL').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let pathologyCategory = result.data
                $('#pathologyUnitId').val(pathologyCategory.id)
                $('#editPathologyUnitName').val(pathologyCategory.name)
                $('#editPathologyUnitsModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenClick('.edit-pathology-unit-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress();
    let pathologyUnitId = $(event.currentTarget).attr('data-id');
    renderPathologyUnitData(pathologyUnitId)
});

listenClick('.delete-pathology-unit-btn', function (event) {
    let pathologyUnitId = $(event.currentTarget).attr('data-id');
    deleteItem($('#pathologyUnitURL').val() + '/' + pathologyUnitId,
        '#pathologyUnitTable', $('#pathologyUnitLang').val());
});
