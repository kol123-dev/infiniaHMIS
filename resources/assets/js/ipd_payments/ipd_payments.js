document.addEventListener("DOMContentLoaded", loadIpdPaymentData);

function loadIpdPaymentData() {
    if (
        !$("#addIpdPaymentNewForm").length &&
        !$("#editIpdPaymentForm").length
    ) {
        return;
    }

    $("#ipdPaymentDate,#editIpdPaymentDate").flatpickr({
        dateFormat: "Y-m-d",
        enableTime: false,
        minDate: $("#showIpdPatientCaseDate").val(),
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
        widgetPositioning: {
            horizontal: "right",
            vertical: "bottom",
        },
    });

    $("#ipdPaymentModeId").select2({
        width: "100%",
        dropdownParent: $("#addIpdPaymentModal"),
    });
    $("#editIpdPaymentModeId").select2({
        width: "100%",
        dropdownParent: $("#editIpdPaymentModal"),
    });
}

listen("click", ".ipdpayment-delete-btn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#showIpdPaymentUrl").val() + "/" + id,
        null,
        $("#ipdPaymentButton").val()
    );
});

listen("click", ".ipdpayment-edit-btn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let ipdPaymentId = $(event.currentTarget).attr("data-id");
    renderIpdPaymentData(ipdPaymentId);
});

listenSubmit("#addIpdPaymentNewForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#btnIpdPaymentSave");
    loadingButton.button("loading");

    var formData = new FormData($(this)[0]);
    $.ajax({
        url: $("#showIpdPaymentCreateUrl").val(),
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function success(result) {
            //IPD Bill Stripe Payment Method
            if (result.data == null) {
                displaySuccessMessage(result.message);
                $("#addIpdPaymentModal").modal("hide");
                Livewire.dispatch("refresh");
            } else {
                if (result.data.payment_type == '3') {
                    let sessionId = result.data[0].sessionId;
                    stripe.redirectToCheckout({
                        sessionId: sessionId,
                    })
                        .then(mainResult => manageAjaxErrors(mainResult));
                }
                if (result.data.payment_type == '4') {
                    let id = result.data.ipdID;
                    $.ajax({
                        url: route('ipdRazorpay.init'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                let { id, amount, ipd_patient_department_id, date, payment_mode, avatar_remove, notes, currency_symbol } = data.data
                                options.order_id = id
                                options.ipd_patient_department_id = ipd_patient_department_id
                                options.amount = amount
                                options.date = date
                                options.payment_mode = payment_mode
                                options.avatar_remove = avatar_remove
                                options.notes = notes
                                options.currency_symbol = currency_symbol

                                let rzp = new Razorpay(options)
                                rzp.open()
                            }
                        },
                        error: function (error) {
                            $("#addIpdPaymentModal").modal("hide");
                            displayErrorMessage(error.responseJSON.message);
                            Livewire.dispatch('refresh');
                        },
                    });
                }
                if (result.data.payment_type == '8') {
                    window.location.href = result.data.url;
                }
                if (result.data.payment_type == '5') {
                    let url = result.data.url;
                    window.location.href = url;
                }
                if (result.data.payment_type == '6') {
                    window.location.replace(route('ipd.paystack.init', {
                        'amount': result.data.amount,
                        'ipdNumber': result.data.ipdID,
                        'notes': result.data.notes
                    }));
                }
            }
        },
        error: function error(result) {
            printErrorMessage("#ipdPaymentValidationErrorsBox", result);
        },
        complete: function complete() {
            loadingButton.button("reset");
        },
    });
});

function renderIpdPaymentData(id) {
    $.ajax({
        url: $("#showIpdPaymentUrl").val() + "/" + id + "/edit",
        type: "GET",
        success: function (result) {
            if (result.success) {
                let ext = result.data.ipd_payment_document_url
                    .split(".")
                    .pop()
                    .toLowerCase();
                if (ext == "pdf") {
                    $("#editIpdPaymentPreviewImage").css(
                        "background-image",
                        'url("' + $(".pdfDocumentImageUrl").val() + '")'
                    );
                } else if (ext == "docx" || ext == "doc") {
                    $("#editIpdPaymentPreviewImage").css(
                        "background-image",
                        'url("' + $(".docxDocumentImageUrl").val() + '")'
                    );
                } else {
                    if (result.data.ipd_payment_document_url != "") {
                        $("#editIpdPaymentPreviewImage").css(
                            "background-image",
                            'url("' +
                            result.data.ipd_payment_document_url +
                            '")'
                        );
                    } else {
                        $("#editIpdPaymentPreviewImage").css(
                            "background-image",
                            'url("' +
                            $(".showDefaultDocumentImageUrl").val() +
                            '")'
                        );
                    }
                }
                $("#ipdPaymentId").val(result.data.id);
                $("#editIpdPaymentAmount").val(result.data.amount);
                document
                    .querySelector("#editIpdPaymentDate")
                    ._flatpickr.setDate(
                        moment(result.data.date).format("YYYY-MM-DD h:mm A")
                    );
                $("#editIpdPaymentNote").val(result.data.notes);
                $("#editIpdPaymentModeId")
                    .val(result.data.payment_mode)
                    .trigger("change.select2");
                $("#editIpdPaymentModal").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
}

listenSubmit("#editIpdPaymentForm", function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find("#btnEditIpdPaymentSave");
    loadingButton.button("loading");
    let id = $("#ipdPaymentId").val();
    let url = $("#showIpdPaymentUrl").val() + "/" + id;
    let data = {
        formSelector: $(this),
        url: url,
        type: "POST",
    };
    editIpdPaymentRecord(data, loadingButton, "#editIpdPaymentModal");
});

listenHiddenBsModal("#addIpdPaymentModal", function () {
    resetModalForm("#addIpdPaymentNewForm", "#ipdPaymentValidationErrorsBox");
    $("#ipdPaymentPreviewImage").attr(
        "src",
        $("#showDefaultDocumentImageUrl").val()
    );
    $("#ipdPaymentPreviewImage").css(
        "background-image",
        'url("' + $("#showDefaultDocumentImageUrl").val() + '")'
    );
});

listenHiddenBsModal("#editIpdPaymentModal", function () {
    resetModalForm("#editIpdPaymentForm", "#editIpdPaymentValidationErrorsBox");
});

listenChange("#ipdPaymentDocumentImage", function () {
    let extension = isValidIpdPaymentDocument(
        $(this),
        "#ipdPaymentValidationErrorsBox"
    );
    if (!isEmpty(extension) && extension != false) {
        $("#ipdPaymentValidationErrorsBox").html("").hide();
        displayDocument(this, "#ipdPaymentPreviewImage", extension);
    }
});

listenChange("#editIpdPaymentDocumentImage", function () {
    let extension = isValidIpdPaymentDocument(
        $(this),
        "#editIpdPaymentValidationErrorsBox"
    );
    if (!isEmpty(extension) && extension != false) {
        $("#editIpdPaymentValidationErrorsBox").html("").hide();
        displayDocument(this, "#editIpdPaymentPreviewImage", extension);
    }
});

function isValidIpdPaymentDocument(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["png", "jpg", "jpeg", "pdf", "doc", "docx"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector)
            .html(Lang.get("js.document_error"))
            .show();
        return false;
    }
    return ext;
}

function deleteItemPaymentAjax(url, tableId, header, callFunction = null) {
    $.ajax({
        url: url,
        type: "DELETE",
        dataType: "json",
        success: function (obj) {
            if (obj.success) {
                Livewire.dispatch("resetPage");
            }
            Swal.fire({
                icon: "success",
                title: "Deleted!",
                confirmButtonColor: "#009ef7",
                text: header + Lang.get("js.has_been_deleted"),
                timer: 2000,
            });
            if (callFunction) {
                eval(callFunction);
            }
        },
        error: function (data) {
            Swal.fire({
                title: "",
                text: data.responseJSON.message,
                confirmButtonColor: "#009ef7",
                icon: "error",
                timer: 5000,
            });
        },
    });
}

window.editIpdPaymentRecord = function (data, loadingButton) {
    var modalSelector =
        arguments.length > 2 && arguments[2] !== undefined
            ? arguments[2]
            : "#EditModal";
    var formData =
        data.formSelector === ""
            ? data.formData
            : new FormData($(data.formSelector)[0]);
    $.ajax({
        url: data.url,
        type: data.type,
        data: formData,
        processData: false,
        contentType: false,
        success: function success(result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $(modalSelector).modal("hide");
                Livewire.dispatch("refresh");
            }
        },
        error: function error(result) {
            UnprocessableInputError(result);
        },
        complete: function complete() {
            loadingButton.button("reset");
        },
    });
};

listen("click", "#ipdPaymentDocumentImage", function () {
    defaultImagePreview("#ipdPaymentPreviewImage");
});

listen("click", ".removeIpdPaymentImageEdit", function () {
    defaultImagePreview("#editIpdPaymentPreviewImage");
});

listenChange("#ipdPaymentModeId", function () {
    let payment_mode = $(this).val();

    if (payment_mode == '3' || payment_mode == '4' || payment_mode == '8' || payment_mode == '6' || payment_mode == '5') {
        $('.ipd_payment_document').addClass('d-none');
    }
    else {
        $('.ipd_payment_document').removeClass('d-none');
    }
});
