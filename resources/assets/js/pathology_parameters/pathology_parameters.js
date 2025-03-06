'use strict';
document.addEventListener('DOMContentLoaded', loadPathologyParameterData)

function loadPathologyParameterData() {
    $('#pathologyParameterUnitId,.edit-unit').select2({
        width: '100%',
    });
}

listenSubmit('#addPathologyParameterForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#pathologyParameterSave');
    loadingButton.button('loading');
    $('#pathologyParameterSave').attr('disabled', true);
    $.ajax({
        url: $('#createPathologyParameterURL').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addPathologyParametersModal').modal('hide');
                Livewire.dispatch('refresh')
                $('#pathologyParameterSave').attr('disabled', false);
            }
        },
        error: function (result) {
            printErrorMessage('#parameterValidationErrorsBox', result);
            $('#pathologyParameterSave').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenSubmit('#editPathologyParameterForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editPathologyParameterSaveBtn');
    loadingButton.button('loading');
    var id = $('#pathologyParameterId').val();
    $('#editPathologyCategorySaveBtn').attr('disabled', true);
    $.ajax({
        url: $('#pathologyParameterURL').val() + '/' + id,
        type: 'patch',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editPathologyParametersModal').modal('hide');
                Livewire.dispatch('refresh')
                $('#editPathologyParameterSaveBtn').attr('disabled', false);
            }
        },
        error: function (result) {
            UnprocessableInputError(result);
            $('#editPathologyParameterSaveBtn').attr('disabled', false);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

listenHiddenBsModal('#addPathologyParametersModal', function () {
    resetModalForm('#addPathologyParameterForm', '#parameterValidationErrorsBox');
    $('#pathologyParameterSave').attr('disabled', false);
});

listenHiddenBsModal('#editPathologyParametersModal', function () {
    resetModalForm('#editPathologyParameterForm', '#editParameterValidationErrorsBox');
    $('#editPathologyParameterSaveBtn').attr('disabled', false);
});

window.renderPathologyParameterData = function (id) {
    $.ajax({
        url: $('#pathologyParameterURL').val() + '/' + id + '/edit',
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let pathologyParameter = result.data
                $('#pathologyParameterId').val(pathologyParameter.id)
                $('#editPathologyParameterName').val(pathologyParameter.parameter_name)
                $('#editParameterRange').val(pathologyParameter.reference_range)
                $('#editPathologyUnitId').
                val(pathologyParameter.unit_id).
                trigger('change')
                $('#editParameterDescription').val(pathologyParameter.description)
                $('#editPathologyParametersModal').modal('show')
                ajaxCallCompleted()
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
};

listenClick('.edit-pathology-parameter-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return
    }
    ajaxCallInProgress();
    let pathologyParameterId = $(event.currentTarget).attr('data-id');
    renderPathologyParameterData(pathologyParameterId)
});

listenClick('.delete-pathology-parameter-btn', function (event) {
    let pathologyParameterId = $(event.currentTarget).attr('data-id');
    deleteItem($('#pathologyParameterURL').val() + '/' + pathologyParameterId,
        '#pathologyParameterTable', $('#pathologyParameterLang').val());
});
