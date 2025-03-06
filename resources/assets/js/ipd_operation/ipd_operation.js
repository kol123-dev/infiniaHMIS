document.addEventListener('DOMContentLoaded', loadIpdOperationData)

function loadIpdOperationData() {
    if (!$('#ipdOperationDate').length) {
        return
    }

    $('#ipdOperationDate').flatpickr({
        enableTime: true,
        defaultDate: new Date(),
        dateFormat: 'Y-m-d H:i',
        locale: $('.userCurrentLanguage').val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom',
        },
    })

    $('#ipdOperationsId, #ipdOperationCategoryId, #ipdOperationDoctorId').
        select2({
            dropdownParent: $('#addIpdOperationModal'),
        })

    $('#editIpdOperationCategoryId, #editIpdOperationsId, #editIpdOperationDoctorId').
        select2({
            dropdownParent: $('#editIpdOperationModal'),
        })
}

let editOperationId = null;

listenClick('.updateIpdOperation', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    $.ajax({
        url: $('.IpdOperationUrl').val() + '/' + id + '/edit',
        method: 'GET',
        success: function (result) {
            editOperationId = result.data.operation_id
            $('#ipdOperationId').val(result.data.id)
            $('#ipdOperationRefNo').val(result.data.ref_no)
            $('#editIpdOperationDate').val(result.data.operation_date)
            $('#editIpdOperationCategoryId').
                val(result.data.operation_category_id).
                trigger('change', [{ onceOnEditRender: true }])
            // $('#editIpdOperationsId').
            //     val(result.data.operation_id).
            //     trigger('change.select2')
            $('#editIpdOperationDoctorId').
                val(result.data.doctor_id).
                trigger('change.select2')
            $('#editIpdAssistantConsultant1').
                val(result.data.assistant_consultant_1)
            $('#editIpdAssistantConsultant2').
                val(result.data.assistant_consultant_2)
            $('#editAnesthetist').val(result.data.anesthetist)
            $('#editAnesthesiaType').val(result.data.anesthesia_type)
            $('#editOTTechnician').val(result.data.ot_technician)
            $('#editOTAssistant').val(result.data.ot_assistant)
            $('#editIpdOperationRemark').val(result.data.remark)
            $('#editIpdOperationResult').val(result.data.result)
            $('#editIpdOperationModal').modal('show')
        },
    })
})

listenChange('#ipdOperationCategoryId',
    function () {
        var id = $(this).val()
        if (id !== '') {
            $.ajax({
                url: $('#operationCategoryChange').val(),
                type: 'GET',
                data: { id: $(this).val() },
                success: function (result) {
                    $('#ipdOperationsId').children().remove()
                    $.each(result, function (i, v) {
                        $('#ipdOperationsId').
                            append(
                                $('<option></option>').attr('value', v).text(i))
                    })
                },
            })
        }
    })

listenChange('#editIpdOperationCategoryId', function (event, onceOnEditRender) {
    var id = $(this).val()
    if (id !== '') {
        $.ajax({
            url: $('#operationCategoryChange').val(),
            type: 'GET',
            data: { id: $(this).val() },
            success: function (result) {
                $('#editIpdOperationsId').children().remove()
                if (typeof onceOnEditRender != 'undefined') {
                    $.each(result, function (i, v) {
                        $('#editIpdOperationsId').
                            append($('<option></option>').attr('value', v).text(i))
                    })
                    $('#editIpdOperationsId').val(editOperationId)
                } else {
                    $.each(result, function (i, v) {
                        $('#editIpdOperationsId').
                            append($('<option></option>').attr('value', v).text(i))
                    })
                }
            },
        })
    }
})

listenSubmit('#addIpdOperationNewForm', function (event) {
    event.preventDefault()
    $.ajax({
        url: $('#addNewOperation').val(),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            displaySuccessMessage(result.message)
            $('#addIpdOperationModal').modal('hide')
            $('#addIpdOperationNewForm')[0].reset()
            Livewire.dispatch('refresh')
        },
    })
})

listen('click', '.deleteIpdOperation', function (event) {
    let id = $(event.currentTarget).attr('data-id')
    deleteItem($('.IpdOperationUrl').val() + '/' + id, '', Lang.get('js.ipd_operation'))
})

listenSubmit('#editIpdOperationNewForm', function (event) {
    event.preventDefault()
    let id = $('#ipdOperationId').val()
    $.ajax({
        url: $('.IpdOperationUrl').val() + '/' + id,
        method: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            displaySuccessMessage(result.message)
            $('#editIpdOperationModal').modal('hide')
            $('#editIpdOperationNewForm')[0].reset()
            Livewire.dispatch('refresh')
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message)
            $('#editIpdOperationModal').modal('hide')
            $('#editIpdOperationNewForm')[0].reset()
            Livewire.dispatch('refresh')
        },
    })
})

listenHiddenBsModal('#addIpdOperationModal', function () {
    resetModalForm('#addIpdOperationNewForm',
        '#ipdOperationValidationErrorsBox')
    $('#ipdOperationCategoryId, #ipdOperationsId, #ipdOperationDoctorId').
        val('').
        trigger('change.select2')
})
