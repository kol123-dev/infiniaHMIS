'use strict';

document.addEventListener("DOMContentLoaded", loadAddOn);

function loadAddOn() {
    listenChange("#addOnDocumentZip", function () {
        let extension = isValidAddOn(
            $(this),
            "#addonErrorsBox"
        );
        if (!isEmpty(extension) && extension != false) {
            $("#addonErrorsBox").html("").hide();

        }
    });
}


function isValidAddOn(inputSelector, validationMessageSelector) {
    let ext = $(inputSelector).val().split(".").pop().toLowerCase()
    if ($.inArray(ext, ["zip"]) == -1) {
        $(inputSelector).val("");
        $(validationMessageSelector)
            .html(Lang.get("The document must be a file of type: zip"))
            .removeClass('d-none hide')
            .show();
        setTimeout(function () {
            $(validationMessageSelector).slideUp(300);
        }, 5000);
        return false;
    }
    return ext;
}


listenSubmit('#addOnForm', function (e) {
    e.preventDefault();
    let loadingButton = jQuery(this).find("#addOnBtnSave");
    loadingButton.attr('disabled', true);

    if ($("#addonErrorsBox").text() !== "") {
        $("#addOnDocumentZip").focus();
        displayErrorMessage($("#addonErrorsBox").text());
        return false;
    }

    let formData = new FormData(this);

    $.ajax({
        url: $("#showAddOnCreateUrl").val(),
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $("#uploadAddOnModal").modal("hide");
                $("#addOnDocumentZip").val("");
                loadingButton.attr('disabled', false);
                setTimeout(function () {
                    window.location.reload()
                }, 3000)
            }
        },
        error: function (result) {
            loadingButton.attr('disabled', false);
            $("#uploadAddOnModal").modal("hide");
            $("#addOnDocumentZip").val("");
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });

})

listenClick('.disableModule', function (e) {
    e.preventDefault();
    let addOnId = $(e.currentTarget).attr('data-id');
    $.ajax({
        url: route('addon.update', addOnId),
        type: "POST",
        data: {
            "id": addOnId
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    window.location.reload()
                }, 4000);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        }

    })

})

listenClick('.deleteAddonModule', function (e) {
    e.preventDefault();
    let addOnId = $(e.currentTarget).attr('data-id');
    let name = $(e.currentTarget).attr('data-title');
    deleteItem(route('addOn.destory', addOnId), ' ', name)
    setTimeout(function () {
        window.location.reload()
    }, 2000)

})
