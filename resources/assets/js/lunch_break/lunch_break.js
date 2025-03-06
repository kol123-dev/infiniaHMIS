document.addEventListener("DOMContentLoaded", loadLunchBreakData);

function loadLunchBreakData() {
    let lang = $(".userCurrentLanguage").val();
    $("#doctorLunchBreakDate").flatpickr({
        locale: lang,
        minDate: new Date(),
        disableMobile: true,
    });

    $(".breakFrom").flatpickr({
        enableTime: true,
        noCalendar: true,
        enableSeconds: true,
        dateFormat: "H:i:S",
        minTime: "00:05:00",
        time_24hr: true,
        position: lang == 'ar' ? 'auto right' : ''
    });
    $(".breakTo").flatpickr({
        enableTime: true,
        noCalendar: true,
        enableSeconds: true,
        dateFormat: "H:i:S",
        time_24hr: true,
        minTime: "00:05:00",
        position: lang == 'ar' ? 'auto right' : ''
    });
}

listenClick("#editOneDay", function () {
    $(".customiseDate").removeClass("d-none");
});

listenClick("#editEveryDay", function () {
    $(".customiseDate").addClass("d-none");
});

listenClick(".doctor-lunch-break-delete-btn", function (event) {
    let lunchBreakRecordId = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("breaks.destroy", lunchBreakRecordId),
        "",
        Lang.get("js.lunch_break")
    );
});

listenSubmit(".doctorBreakForm", function (e) {
    e.preventDefault();
    let breakStartTime = $(".breakFrom").val();
    let breakEndTime = $(".breakTo").val();
    let date = $("#doctorLunchBreakDate").val();

    const singleDay = $("#editOneDay").prop("checked");

    if (singleDay && date === "") {
        displayErrorMessage(Lang.get("js.date_required"));
        return false;
    }

    if (breakStartTime == "00:00:00") {
        displayErrorMessage(Lang.get("js.break_time_greater_than_zero"));
        return false;
    }
    if (breakEndTime == "00:00:00") {
        displayErrorMessage(Lang.get("js.break_to_time_greater_than_zero"));
        return false;
    }
    if (breakEndTime == breakStartTime) {
        displayErrorMessage(Lang.get("js.break_to_time_greater_than_from_time"));
        return false;
    }
    $(this)[0].submit();
});
