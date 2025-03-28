document.addEventListener("DOMContentLoaded", loadUpdateInvoiceData);

function loadUpdateInvoiceData() {
    if (!$(".invoiceSaveUrl")) {
        return;
    }

    $('input:text:not([readonly="readonly"])').first().blur();

    $("#invoicePatientId").focus();

    $(".accountId").select2({
        width: "100%",
    });

    $("#invoice_date").flatpickr({
        defaultDate: new Date(),
        dateFormat: "Y-m-d",
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    $("#editInvoiceDate").flatpickr({
        dateFormat: "Y-m-d",
        locale: $(".userCurrentLanguage").val(),
        position: $(".userCurrentLanguage").val() == 'ar' ? 'auto right' : '',
    });

    window.isNumberKey = (evt, element) => {
        let charCode = evt.which ? evt.which : event.keyCode;

        return !(
            (charCode !== 46 || $(element).val().indexOf(".") !== -1) &&
            (charCode < 48 || charCode > 57)
        );
    };

    listenClick(".deleteInvoiceItem", function () {
        $(this).parents("tr").remove();
        resetInvoiceItemIndex();
        calculateAndSetInvoiceAmount();
    });

    listenKeyup(".qty", function () {
        let qty = parseFloat($(this).val());
        let rate = $(this).parent().siblings().find(".price").val();
        rate = parseFloat(removeCommas(rate));
        let amount = calculateAmount(qty, rate);
        $(this).parent().siblings(".amount").text(addCommas(amount.toFixed(2)));
        calculateAndSetInvoiceAmount();
    });

    listenKeyup(".price", function () {
        let rate = $(this).val();
        rate = parseFloat(removeCommas(rate));
        let qty = parseFloat($(this).parent().siblings().find(".qty").val());
        let amount = calculateAmount(qty, rate);
        $(this).parent().siblings(".amount").text(addCommas(amount.toFixed(2)));
        calculateAndSetInvoiceAmount();
    });

    const calculateAmount = (qty, rate) => {
        if (qty > 0 && rate > 0) {
            return qty * rate;
        } else {
            return 0;
        }
    };

    const calculateAndSetInvoiceAmount = () => {
        let totalAmount = 0;
        $(".invoice-item-container>tr").each(function () {
            let itemTotal = $(this).find(".item-total").text();
            itemTotal = removeCommas(itemTotal);
            itemTotal = isEmpty($.trim(itemTotal)) ? 0 : parseFloat(itemTotal);
            totalAmount += itemTotal;
        });
        totalAmount = parseFloat(totalAmount);

        $("#total").text(addCommas(totalAmount.toFixed(2)));

        //set hidden input value
        $("#total_amount").val(totalAmount);

        calculateDiscount();
    };

    const calculateDiscount = () => {
        let discount = $("#discount").val();
        let totalAmount = removeCommas($("#total").text());

        if (isEmpty(discount) || isEmpty(totalAmount)) {
            discount = 0;
        }

        let discountAmount = (totalAmount * discount) / 100;
        let finalAmount = totalAmount - discountAmount;

        $("#finalAmount").text(addCommas(finalAmount.toFixed(2)));
        $("#total_amount").val(finalAmount.toFixed(2));
        $("#discountAmount").text(addCommas(discountAmount.toFixed(2)));
    };

    listenKeyup("#discount", function (e) {
        calculateDiscount();
    });
}

listenSubmit(".invoiceForm", function (event) {
    event.preventDefault();
    // screenLock();
    let formData = new FormData($(this)[0]);
    $.ajax({
        url: $(".invoiceSaveUrl").val(),
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            displaySuccessMessage(result.message);
            setTimeout(function () {
                window.location.href = $(".invoiceUrl").val();
            }, 1000);
        },
        error: function (result) {
            printErrorMessage("#validationErrorsBox", result);
        },
        // complete: function () {
        //     screenUnLock();
        // },
    });
});

listenClick("#addInvoiceItem", function () {
    let uniqueId = $(".uniqueId").val();

    let data = {
        accounts: JSON.parse($(".invoiceAccounts").val()),
        uniqueId: uniqueId,
    };
    let invoiceItemHtml = prepareTemplateRender("#invoiceItemTemplate", data);
    $(".invoice-item-container").append(invoiceItemHtml);
    dropdownToSelect2Account(".accountId");
    uniqueId++;

    resetInvoiceItemIndex();
});

const resetInvoiceItemIndex = () => {
    let index = 1;
    $(".invoice-item-container>tr").each(function () {
        $(this).find(".item-number").text(index);
        index++;
    });
    if (index - 1 == 0) {
        let uniqueId = $(".uniqueId").val();
        let data = {
            accounts: JSON.parse($(".invoiceAccounts").val()),
            uniqueId: uniqueId,
        };
        let invoiceItemHtml = prepareTemplateRender(
            "#invoiceItemTemplate",
            data
        );
        $(".invoice-item-container").append(invoiceItemHtml);
        dropdownToSelect2Account(".accountId");
        uniqueId++;
    }
};

const dropdownToSelect2Account = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("js.choose") + " " + Lang.get("js.account"),
        width: "100%",
    });
};
