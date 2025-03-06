document.addEventListener("DOMContentLoaded", loadRadiologyEdit);

function loadRadiologyEdit() {
    if (!$(".radiologyTestActionURL").length) {
        return;
    }

    $(".price-input").trigger("input");
    $(".radiologyCategories,.chargeCategories,#editChargeCategories").select2({
        width: "100%",
    });
    $("#createRadiologyTest, #editRadiologyTest")
        .find("input:text:visible:first")
        .focus();
}

window.radiologyTestGetStandardCharge = function (id) {
    $.ajax({
        url: route("radiology.test.charge", id),
        method: "get",
        cache: false,
        success: function (result) {
            if (result.data !== "") {
                if (result.data !== "") {
                    $("#chargeCode").empty();
                    $("#chargeCode").removeAttr("disabled");
                    $("#chargeCode").append(
                        $("<option></option>").text(
                            Lang.get("js.choose") +
                                " " +
                                Lang.get("js.charge")
                        )
                    );
                    $.each(result.data, function (i, v) {
                        $("#chargeCode").append(
                            $("<option></option>").attr("value", i).text(v)
                        );
                    });
                } else {
                    $("#chargeCode").append(
                        $("<option></option>").text(
                            Lang.get("js.choose") +
                                " " +
                                Lang.get("js.charge")
                        )
                    );
                }
            }
        },
    });
};

listenChange(".chargeCategories", function (event) {
    let chargeCategoryId = $(this).val();
    chargeCategoryId !== ""
        ? radiologyTestGetStandardCharge(chargeCategoryId)
        : $(".rtStandardCharge").val("");
});

$("#createRadiologyTest, #editRadiologyTest")
    .find("input:text:visible:first")
    .focus();

listen("change", ".charge-category", function (event) {
    let chargeCategoryId = $(this).val();
    chargeCategoryId !== ""
        ? getRadiologyChargeCode(chargeCategoryId)
        : $("#chargeCode").empty();
    $("#chargeCode").attr("disabled", true),
        $("#chargeCode").append(
            $("<option></option>").text(
                Lang.get("js.choose") +
                    " " +
                    Lang.get("js.charge")
            )
        ),
        $(".rtStandardCharge").val("");
});

window.getRadiologyChargeCode = function (id) {
    $.ajax({
        url: route("radiology.test.charge", id),
        method: "get",
        cache: false,
        success: function (result) {
            if (result.data !== "") {
                $("#chargeCode").empty();
                $("#chargeCode").removeAttr("disabled");
                $("#chargeCode").append(
                    $("<option></option>").text(
                        Lang.get("js.choose") +
                            " " +
                            Lang.get("js.charge")
                    )
                );
                $.each(result.data, function (i, v) {
                    $("#chargeCode").append(
                        $("<option></option>").attr("value", i).text(v)
                    );
                });
            } else {
                $("#chargeCode").append(
                    $("<option></option>").text(
                        Lang.get("js.choose") +
                            " " +
                            Lang.get("js.charge")
                    )
                );
            }
        },
    });
};

listen("change", ".charge-code", function (event) {
    let chargeId = $(this).val();
    chargeId !== ""
        ? getRadiologyStandardCharge(chargeId)
        : $(".rd-test-standard-charge").val("");
});

window.getRadiologyStandardCharge = function (id) {
    $.ajax({
        url: route("radiology.test.charge.code", id),
        method: "get",
        cache: false,
        success: function (result) {
            if (result !== "") {
                $(".rtStandardCharge").val(result.data);
            }
        },
    });
};
