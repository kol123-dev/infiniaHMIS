"use strict";
// document.addEventListener("DOMContentLoaded", loadBillData);

Livewire.hook("element.init", ({ component }) => {
    if(component.name == "bill-table"){
        loadBillData()
    }
});

function loadBillData() {
    Lang.setLocale($(".userCurrentLanguage").val());

    $('.paymentModeType').select2({
        width: '100%',
    });
}

listenChange(".paymentModeType", function () {
    swal({
        title: Lang.get("js.are_you_sure"),
        text: Lang.get("js.complete_this_payment"),
        icon: "warning",
        buttons: {
            confirm: $(".yesVariable").val(),
            cancel: $(".noVariable").val() + ", " + $(".cancelVariable").val(),
        },
    }).then((result) => {
        if (result) {
            let id = $(this).data("id");
            let payment_type = $(this).val();

            $.ajax({
                url: route("manual-billing-payments.store"),
                type: "POST",
                data: { id: id, payment_type: payment_type },
                success: function (data) {
                    if (data.data == null) {
                        displaySuccessMessage(data.message);
                        Livewire.dispatch("refresh");
                    }else{
                        // Stripe payment
                        if (data.data.payment_type == "0") {
                            let sessionId = data.data[0].sessionId;
                            stripe.redirectToCheckout({
                                sessionId: sessionId,
                            })
                            .then(mainResult => manageAjaxErrors(mainResult));
                        }
                        // Razorpay payment
                        if(data.data.payment_type == "2"){
                            let billId = data.data.bill_id;
                            $.ajax({
                                type: 'POST',
                                url: route('razorpay.init'),
                                data: {'bill_id': billId},
                                success: function (result) {
                                    if (result.success) {
                                        let {id, amount} = result.data
                                        options.amount = amount
                                        options.order_id = id

                                        let rzp = new Razorpay(options)
                                        rzp.open()
                                    }
                                },
                                error: function (error){
                                    displayErrorMessage(error.responseJSON.message);
                                    Livewire.dispatch('refresh');
                                },
                            })
                        }
                        //Flutterwave Payment`
                        if(data.data.payment_type == '8'){
                            window.location.href = data.data.url;
                        }
                        //PhonePe payment
                        if(data.data.payment_type == '5'){
                            window.location.href = data.data.url;
                        }
                        //Paystack Payment
                        if(data.data.payment_type == "3"){
                            let billId = data.data.bill_id;
                            window.location.replace(route('manual.paystack.init', {'bill_id': billId}));
                        }
                    }
                },
                error: function (error){
                    displayErrorMessage(error.responseJSON.message);
                    Livewire.dispatch('refresh');
                }
            });
        } else {
            Livewire.dispatch("refresh");
        }
    });
});
