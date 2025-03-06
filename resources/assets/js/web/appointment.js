"use strict";

document.addEventListener("DOMContentLoaded", loadWebAppointmentDate);

function loadWebAppointmentDate() {
    if (!$("#webAppointmentFormSubmit").length) {
        return;
    }

    var customDate = $("#customFieldDate").val();
    var customDateTime = $("#customFieldDateTime").val();

    $("#customFieldDate").datepicker({
        format: "Y-m-d",
        useCurrent: false,
        sideBySide: true,
        minDate: customDateTime ? customDateTime : new Date(),
    });

    $("#customFieldDateTime").flatpickr({
        format: "Y-m-d H:i",
        enableTime: true,
        locale: $(".userCurrentLanguage").val(),
        defaultDate: customDateTime ? customDateTime : new Date(),
        onOpen: function (selectedDates, dateStr, instance) {
            var calendar = instance.calendarContainer;
            calendar.classList.add("custom-calendar");
            var todayElement = calendar.querySelector(".flatpickr-day.today");
            if (todayElement) {
                todayElement.classList.add("selected");
            }
        },
    });
    // $("#customFieldDateTime").datepicker({
    //     format: "Y-m-d H:i",
    //     useCurrent: false,
    //     sideBySide: true,
    //     minDate: customDateTime ? customDateTime : new Date(),
    // });

    $(".custom-field-select").selectize();
    $(".custom-field-multi-select").selectize();

    if (!$("#opdDate").length) {
        return;
    }

    $("#advancePaymentPatientId").selectize();
    $("#webDepartmentId").selectize();
    $("#appointmentDoctorId").selectize();
    $("#appointmentPaymentModeId").selectize();

    var doctor = $("#doctor").val();
    Lang.setLocale($(".userCurrentLanguage").val());

    let opdDate = $("#opdDate").datepicker({
        useCurrent: false,
        sideBySide: true,
        isRTL: false,
        minDate: new Date(),
        // dateFormat: 'dd/mm/yy',
        onSelect: function (selectedDate, dateStr) {
            let selectDate = selectedDate;
            dateSelectSlot = selectedDate;
            $(".doctor-schedule").css("display", "none");
            $(".error-message").css("display", "none");
            $(".available-slot-heading").css("display", "none");
            $(".color-information").css("display", "none");
            $(".time-slot").remove();
            if ($("#webDepartmentId").val() == "") {
                $("#validationErrorsBox")
                    .show()
                    .html(Lang.get("js.please_select_doctor_department"));
                $("#validationErrorsBox").delay(5000).fadeOut();
                $("#opdDate").val("");
                // opdDate.clear();
                return false;
            } else if ($("#appointmentDoctorId").val() == "") {
                $("#validationErrorsBox")
                    .show()
                    .html(Lang.get("js.please_select_doctor"));
                $("#validationErrorsBox").delay(5000).fadeOut();
                $("#opdDate").val("");
                // opdDate.clear();
                return false;
            }
            var weekday = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            var selected = new Date(selectedDate);
            var selectedAppDate = $(this).datepicker("getDate");
            let dayName = weekday[selectedAppDate.getDay()];
            var appointmentBreakIntervals;
            selectedDate = dateStr;

            //if dayName is blank, then ajax call not run.
            if (dayName == null || dayName == "") {
                return false;
            }

            //get doctor schedule list with time slot.
            $.ajax({
                type: "GET",
                url: $("#homeDoctorScheduleList").val(),
                data: {
                    day_name: dayName,
                    doctor_id: doctorId,
                    date: moment(new Date(selectDate)).format("YYYY-MM-DD"),
                },
                success: function (result) {
                    if (result.success) {
                        if (result.data != "") {
                            if (result.data.scheduleDay.length != 0 &&
                                result.data.doctorHoliday.length == 0) {
                                let availableFrom = "";
                                if (
                                    moment(new Date()).format("MM/DD/YYYY") ===
                                    selectDate
                                ) {
                                    // availableFrom = moment(new Date()).
                                    // format('H:mm:ss')
                                    availableFrom = moment().ceil(
                                        moment
                                            .duration(
                                                result.data.perPatientTime[0]
                                                    .per_patient_time
                                            )
                                            .asMinutes(),
                                        "minute"
                                    );
                                    availableFrom = moment(
                                        availableFrom.toString()
                                    ).format("H:mm:ss");

                                    // availableFrom = moment.duration(
                                    //     result.data.perPatientTime[0].per_patient_time).
                                    //     asMinutes()
                                    // availableFrom = moment(
                                    //     availableFrom.toString()).
                                    //     format('H:mm:ss')
                                    // availableFrom = moment().ceil(moment.duration( result.data.perPatientTime[0].per_patient_time).asMinutes(), 'minute');
                                    // availableFrom = moment.duration( result.data.perPatientTime[0].per_patient_time).asMinutes();
                                    // availableFrom = moment(availableFrom.toString()).format('H:mm:ss');
                                    // console.log(availableFrom)
                                    // availableFrom = moment(new Date()).
                                    //     add(result.data.perPatientTime[0].per_patient_time,
                                    //         'minutes').
                                    //     format('H:mm:ss')
                                } else {
                                    availableFrom =
                                        result.data.scheduleDay[0]
                                            .available_from;
                                }
                                var doctorStartTime =
                                    selectedDate + " " + availableFrom;
                                var doctorEndTime =
                                    selectedDate +
                                    " " +
                                    result.data.scheduleDay[0].available_to;
                                var doctorPatientTime =
                                    result.data.perPatientTime[0]
                                        .per_patient_time;
                                // console.log(moment(new Date()).format('LTS'))
                                // console.log(result.data.scheduleDay[0].available_to)
                                // console.log(moment(new Date()).format('LTS') > result.data.scheduleDay[0].available_to)
                                //perPatientTime convert to Minute
                                var a = doctorPatientTime.split(":"); // split it at the colons
                                var minutes = +a[0] * 60 + +a[1]; // convert to minute

                                //parse In
                                var startTime = parseIn(doctorStartTime);
                                // let now =  new Date();
                                // if(selectedDate.selectedDay == now.getDate()){
                                //     startTime.setTime(startTime.getTime() + 1000 * 60);
                                // }
                                var endTime = parseIn(doctorEndTime);
                                //call to getTimeIntervals function
                                intervals = getTimeIntervals(
                                    startTime,
                                    endTime,
                                    minutes
                                );
                                if (result.data.doctorBreak != null) {
                                    for (
                                        var breakIndex = 0;
                                        breakIndex <
                                        result.data.doctorBreak.length;
                                        ++breakIndex
                                    ) {
                                        var startBreakTime = parseIn(
                                            selectedDate +
                                            " " +
                                            result.data.doctorBreak[
                                                breakIndex
                                            ].break_from
                                        );

                                        var endBreakTime = parseIn(
                                            selectedDate +
                                            " " +
                                            result.data.doctorBreak[
                                                breakIndex
                                            ].break_to
                                        );

                                        appointmentBreakIntervals =
                                            getTimeIntervals(
                                                startBreakTime,
                                                endBreakTime,
                                                1
                                            );
                                        intervals = intervals.filter(
                                            (slot) =>
                                                !appointmentBreakIntervals.includes(
                                                    slot
                                                )
                                        );
                                    }
                                }
                                //if intervals array length is grater then 0 then process
                                if (intervals.length > 0) {
                                    $(".available-slot-heading").css(
                                        "display",
                                        "block"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "block"
                                    );
                                    var index;
                                    let timeStlots = "";
                                    for (
                                        index = 0;
                                        index < intervals.length;
                                        ++index
                                    ) {
                                        let data = [
                                            {
                                                index: index,
                                                timeSlot: intervals[index],
                                            },
                                        ];
                                        var timeSlot = prepareTemplateRender(
                                            "#appointmentSlotTemplate",
                                            data
                                        );
                                        timeStlots += timeSlot;
                                    }
                                    $(".available-slot").append(timeStlots);
                                }
                                // display Day Name and time
                                if (
                                    availableFrom != "00:00:00" &&
                                    result.data.scheduleDay[0].available_to !=
                                    "00:00:00" &&
                                    doctorStartTime != doctorEndTime
                                ) {
                                    $(".doctor-schedule").css(
                                        "display",
                                        "block"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "block"
                                    );
                                    $(".day-name").html(
                                        Lang.get('js.' + result.data.scheduleDay[0].available_on)
                                    );
                                    $(".schedule-time").html(
                                        "[" +
                                        availableFrom +
                                        " - " +
                                        result.data.scheduleDay[0]
                                            .available_to +
                                        "]"
                                    );
                                } else {
                                    $(".doctor-schedule").css(
                                        "display",
                                        "none"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "none"
                                    );
                                    $(".error-message").css("display", "block");
                                    $(".error-message").html(
                                        Lang.get(
                                            "js.doctor_schedule_not_available_on_this_date"
                                        )
                                    );
                                }
                            } else {
                                $(".doctor-schedule").css("display", "none");
                                $(".color-information").css("display", "none");
                                $(".error-message").css("display", "block");
                                $(".error-message").html(
                                    Lang.get(
                                        "js.doctor_schedule_not_available_on_this_date"
                                    )
                                );
                            }
                        }
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                },
            });

            if ($("#homeIsCreate").val() || $("#homeIsEdit").val()) {
                var delayCall = 200;
                setTimeout(getCreateTimeSlot, delayCall);
                let slotsData = null;

                function getCreateTimeSlot() {
                    if ($("#homeIsCreate").val()) {
                        slotsData = {
                            editSelectedDate: moment(
                                $("#opdDate").datepicker("getDate")
                            ).format("MM/DD/YYYY"),
                            doctor_id: doctorId,
                        };
                    } else {
                        slotsData = {
                            editSelectedDate: moment(
                                $("#opdDate").datepicker("getDate")
                            ).format("MM/DD/YYYY"),
                            editId: appointmentEditId,
                            doctor_id: doctorId,
                        };
                    }

                    $.ajax({
                        url: $("#homeGetBookingSlot").val(),
                        type: "GET",
                        data: slotsData,
                        success: function (result) {
                            alreadyCreateTimeSlot = result.data.bookingSlotArr;
                            if (result.data.hasOwnProperty("onlyTime")) {
                                if (result.data.bookingSlotArr.length > 0) {
                                    editTimeSlot =
                                        result.data.onlyTime.toString();
                                    $.each(
                                        result.data.bookingSlotArr,
                                        function (index, value) {
                                            $.each(intervals, function (i, v) {
                                                if (value == v) {
                                                    $(".time-interval").each(
                                                        function () {
                                                            if (
                                                                $(this).data(
                                                                    "id"
                                                                ) == i
                                                            ) {
                                                                if (
                                                                    $(
                                                                        this
                                                                    ).html() !=
                                                                    editTimeSlot
                                                                ) {
                                                                    $(this)
                                                                        .parent()
                                                                        .css({
                                                                            "background-color":
                                                                                "#ffa721",
                                                                            border: "1px solid #ffa721",
                                                                            color: "#ffffff",
                                                                        });
                                                                    $(this)
                                                                        .parent()
                                                                        .addClass(
                                                                            "booked"
                                                                        );
                                                                    $(this)
                                                                        .parent()
                                                                        .children()
                                                                        .prop(
                                                                            "disabled",
                                                                            true
                                                                        );
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                            });
                                        }
                                    );
                                }
                                $(".time-interval").each(function () {
                                    if (
                                        $(this).html() == editTimeSlot &&
                                        result.data.bookingSlotArr.length > 0
                                    ) {
                                        $(this)
                                            .parent()
                                            .addClass("time-slot-book");
                                        $(this).parent().removeClass("booked");
                                        $(this)
                                            .parent()
                                            .children()
                                            .prop("disabled", false);
                                        $(this).click();
                                    }
                                });
                            } else if (alreadyCreateTimeSlot.length > 0) {
                                $.each(
                                    alreadyCreateTimeSlot,
                                    function (index, value) {
                                        $.each(intervals, function (i, v) {
                                            if (value === v) {
                                                $(".time-interval").each(
                                                    function () {
                                                        if (
                                                            $(this).data(
                                                                "id"
                                                            ) === i
                                                        ) {
                                                            $(this)
                                                                .parent()
                                                                .addClass(
                                                                    "time-slot-book"
                                                                );
                                                            $(
                                                                ".time-slot-book"
                                                            ).css({
                                                                "background-color":
                                                                    "#FF8E4B",
                                                                border: "1px solid #FF8E4B",
                                                                color: "#ffffff",
                                                            });
                                                            $(this)
                                                                .parent()
                                                                .addClass(
                                                                    "booked"
                                                                );
                                                            $(this)
                                                                .parent()
                                                                .children()
                                                                .prop(
                                                                    "disabled",
                                                                    true
                                                                );
                                                        }
                                                    }
                                                );
                                            }
                                        });
                                    }
                                );
                            }
                        },
                    });
                }
            }
        },
    });

    // opdDate.datepicker(
    //     $.extend({}, $.datepicker.regional[$('.userCurrentLanguage').val()]))

    // if edit record then trigger change
    var editTimeSlot;
    if ($("#homeIsEdit").val()) {
        $("#appointmentDoctorId").trigger("change", function (event) {
            doctorId = $(this).val();
        });

        $("#opdDate").trigger("dp.change", function () {
            var selected = new Date($(this).val());
        });
    }

    $(".old-patient-email").focusout(function () {
        let email = $(".old-patient-email").val();
        if (oldPatient && email != "") {
            $.ajax({
                url: "appointments" + "/" + email + "/patient-detail",
                type: "get",
                success: function (result) {
                    if (result.data != null) {
                        $("#patient").empty();
                        $.each(result.data, function (index, value) {
                            $("#patientName").val(value);
                            $("#patient").val(index);
                        });
                    } else {
                        displayErrorMessage(
                            Lang.get(
                                "js.patient_not_exists_or_status_is_not_active"
                            )
                        );
                    }
                },
            });
        }
    });

    if (!$("#appointmentDate").val()) {
        return;
    }
    let appointmentDate = $("#appointmentDate").val();
    // var doctor = $('#doctor').val()
    if (appointmentDate !== null) {
        loadAppointmentDate();
    }

    function loadAppointmentDate() {
        opdDate.datepicker("setDate", appointmentDate);
        // opdDate.datepicker($.extend({},
        //     $.datepicker.regional[$('.userCurrentLanguage').val()]))
        if (opdDate !== null) {
            opdDate instanceof Date;
            let dateStr = opdDate;
            let selectedDate = appointmentDate;
            $(".doctor-schedule").css("display", "none");
            $(".error-message").css("display", "none");
            $(".available-slot-heading").css("display", "none");
            $(".color-information").css("display", "none");
            $(".time-slot").remove();
            // if ($('#departmentId').val() == '') {
            //     $('#validationErrorsBox').
            //         show().
            //         html('Please select Doctor Department');
            //     $('#validationErrorsBox').delay(5000).fadeOut();
            //     $('#opdDate').val('');
            //     // opdDate.clear();
            //     return false;
            // } else if ($('#doctorId').val() == '') {
            //     $('#validationErrorsBox').show().html('Please select Doctor');
            //     $('#validationErrorsBox').delay(5000).fadeOut();
            //     $('#opdDate').val('');
            //     // opdDate.clear();
            //     return false;
            // }
            var weekday = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            var selected = new Date(selectedDate);
            var selectedAppDate = opdDate.datepicker("getDate");
            let dayName = weekday[selectedAppDate.getDay()];
            // let dayName = weekday[selected.getDay()]
            var appointmentBreakIntervals;
            selectedDate = dateStr;

            //if dayName is blank, then ajax call not run.
            if (dayName == null || dayName == "") {
                return false;
            }

            //get doctor schedule list with time slot.
            $.ajax({
                type: "GET",
                url: $("#homeDoctorScheduleList").val(),
                data: {
                    day_name: dayName,
                    doctor_id: doctor,
                    date: moment(new Date(appointmentDate)).format("YYYY-MM-DD"),
                },
                success: function (result) {
                    if (result.success) {
                        if (result.data != "") {
                            if (result.data.scheduleDay.length != 0 &&
                                result.data.doctorHoliday.length == 0) {
                                let availableFrom = "";

                                if (
                                    moment(new Date()).format("MM/DD/YYYY") ===
                                    appointmentDate
                                ) {
                                    // availableFrom = moment(new Date()).
                                    // add(result.data.perPatientTime[0].per_patient_time,
                                    //     'minutes').
                                    // format('H:mm:ss')
                                    // availableFrom = moment.duration(
                                    //     result.data.perPatientTime[0].per_patient_time).
                                    //     asMinutes()
                                    // availableFrom = moment(
                                    //     availableFrom.toString()).
                                    //     format('H:mm:ss')
                                    availableFrom = moment().ceil(
                                        moment
                                            .duration(
                                                result.data.perPatientTime[0]
                                                    .per_patient_time
                                            )
                                            .asMinutes(),
                                        "minute"
                                    );
                                    // availableFrom = moment.duration( result.data.perPatientTime[0].per_patient_time).asMinutes();
                                    availableFrom = moment(
                                        availableFrom.toString()
                                    ).format("H:mm:ss");
                                    // availableFrom = moment(new Date()).
                                    //     add(result.data.perPatientTime[0].per_patient_time,
                                    //         'minutes').
                                    //     format('H:mm:ss')
                                } else {
                                    availableFrom =
                                        result.data.scheduleDay[0]
                                            .available_from;
                                }
                                var doctorStartTime =
                                    selectedDate + " " + availableFrom;
                                var doctorEndTime =
                                    selectedDate +
                                    " " +
                                    result.data.scheduleDay[0].available_to;

                                var doctorPatientTime =
                                    result.data.perPatientTime[0]
                                        .per_patient_time;

                                //perPatientTime convert to Minute
                                var a = doctorPatientTime.split(":"); // split it at the colons
                                var minutes = +a[0] * 60 + +a[1]; // convert to minute

                                //parse In
                                var startTime = parseIn(doctorStartTime);
                                var endTime = parseIn(doctorEndTime);

                                //call to getTimeIntervals function
                                intervals = getTimeIntervals(
                                    startTime,
                                    endTime,
                                    minutes
                                );
                                if (result.data.doctorBreak != null) {
                                    for (
                                        var breakIndex = 0;
                                        breakIndex <
                                        result.data.doctorBreak.length;
                                        ++breakIndex
                                    ) {
                                        var startBreakTime = parseIn(
                                            selectedDate +
                                            " " +
                                            result.data.doctorBreak[
                                                breakIndex
                                            ].break_from
                                        );

                                        var endBreakTime = parseIn(
                                            selectedDate +
                                            " " +
                                            result.data.doctorBreak[
                                                breakIndex
                                            ].break_to
                                        );

                                        appointmentBreakIntervals =
                                            getTimeIntervals(
                                                startBreakTime,
                                                endBreakTime,
                                                1
                                            );
                                        intervals = intervals.filter(
                                            (slot) =>
                                                !appointmentBreakIntervals.includes(
                                                    slot
                                                )
                                        );
                                    }
                                }
                                //if intervals array length is grater then 0 then process
                                if (intervals.length > 0) {
                                    $(".available-slot-heading").css(
                                        "display",
                                        "block"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "block"
                                    );
                                    var index;
                                    let timeStlots = "";
                                    for (
                                        index = 0;
                                        index < intervals.length;
                                        ++index
                                    ) {
                                        let data = [
                                            {
                                                index: index,
                                                timeSlot: intervals[index],
                                            },
                                        ];
                                        var timeSlot = prepareTemplateRender(
                                            "#appointmentSlotTemplate",
                                            data
                                        );
                                        timeStlots += timeSlot;
                                    }
                                    $(".available-slot").append(timeStlots);
                                }

                                // display Day Name and time
                                if (
                                    availableFrom != "00:00:00" &&
                                    result.data.scheduleDay[0].available_to !=
                                    "00:00:00" &&
                                    doctorStartTime != doctorEndTime
                                ) {
                                    $(".doctor-schedule").css(
                                        "display",
                                        "block"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "block"
                                    );
                                    $(".day-name").html(
                                        Lang.get('js.' + result.data.scheduleDay[0].available_on)
                                    );
                                    $(".schedule-time").html(
                                        "[" +
                                        availableFrom +
                                        " - " +
                                        result.data.scheduleDay[0]
                                            .available_to +
                                        "]"
                                    );
                                } else {
                                    $(".doctor-schedule").css(
                                        "display",
                                        "none"
                                    );
                                    $(".color-information").css(
                                        "display",
                                        "none"
                                    );
                                    $(".error-message").css("display", "block");
                                    $(".error-message").html(
                                        Lang.get(
                                            "js.doctor_schedule_not_available_on_this_date"
                                        )
                                    );
                                }
                            } else {
                                $(".doctor-schedule").css("display", "none");
                                $(".color-information").css("display", "none");
                                $(".error-message").css("display", "block");
                                $(".error-message").html(
                                    Lang.get(
                                        "js.doctor_schedule_not_available_on_this_date"
                                    )
                                );
                            }
                        }
                    }
                },
            });

            if ($("#homeIsCreate").val() || $("#homeIsEdit").val()) {
                var delayCall = 200;
                setTimeout(getCreateTimeSlot, delayCall);
                let slotsData = null;

                function getCreateTimeSlot() {
                    if ($("#homeIsCreate").val()) {
                        slotsData = {
                            editSelectedDate: moment(
                                $("#opdDate").datepicker("getDate")
                            ).format("MM/DD/YYYY"),
                            doctor_id: doctorId,
                        };
                    } else {
                        slotsData = {
                            editSelectedDate: moment(
                                $("#opdDate").datepicker("getDate")
                            ).format("MM/DD/YYYY"),
                            editId: appointmentEditId,
                            doctor_id: doctorId,
                        };
                    }

                    $.ajax({
                        url: $("#homeGetBookingSlot").val(),
                        type: "GET",
                        data: slotsData,
                        success: function (result) {
                            alreadyCreateTimeSlot = result.data.bookingSlotArr;
                            if (result.data.hasOwnProperty("onlyTime")) {
                                if (result.data.bookingSlotArr.length > 0) {
                                    editTimeSlot =
                                        result.data.onlyTime.toString();
                                    $.each(
                                        result.data.bookingSlotArr,
                                        function (index, value) {
                                            $.each(intervals, function (i, v) {
                                                if (value == v) {
                                                    $(".time-interval").each(
                                                        function () {
                                                            if (
                                                                $(this).data(
                                                                    "id"
                                                                ) == i
                                                            ) {
                                                                if (
                                                                    $(
                                                                        this
                                                                    ).html() !=
                                                                    editTimeSlot
                                                                ) {
                                                                    $(this)
                                                                        .parent()
                                                                        .css({
                                                                            "background-color":
                                                                                "#ffa721",
                                                                            border: "1px solid #ffa721",
                                                                            color: "#ffffff",
                                                                        });
                                                                    $(this)
                                                                        .parent()
                                                                        .addClass(
                                                                            "booked"
                                                                        );
                                                                    $(this)
                                                                        .parent()
                                                                        .children()
                                                                        .prop(
                                                                            "disabled",
                                                                            true
                                                                        );
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                            });
                                        }
                                    );
                                }
                                $(".time-interval").each(function () {
                                    if (
                                        $(this).html() == editTimeSlot &&
                                        result.data.bookingSlotArr.length > 0
                                    ) {
                                        $(this)
                                            .parent()
                                            .addClass("time-slot-book");
                                        $(this).parent().removeClass("booked");
                                        $(this)
                                            .parent()
                                            .children()
                                            .prop("disabled", false);
                                        $(this).click();
                                    }
                                });
                            } else if (alreadyCreateTimeSlot.length > 0) {
                                $.each(
                                    alreadyCreateTimeSlot,
                                    function (index, value) {
                                        $.each(intervals, function (i, v) {
                                            if (value === v) {
                                                $(".time-interval").each(
                                                    function () {
                                                        if (
                                                            $(this).data(
                                                                "id"
                                                            ) === i
                                                        ) {
                                                            $(this)
                                                                .parent()
                                                                .addClass(
                                                                    "time-slot-book"
                                                                );
                                                            $(
                                                                ".time-slot-book"
                                                            ).css({
                                                                "background-color":
                                                                    "#FF8E4B",
                                                                border: "1px solid #FF8E4B",
                                                                color: "#ffffff",
                                                            });
                                                            $(this)
                                                                .parent()
                                                                .addClass(
                                                                    "booked"
                                                                );
                                                            $(this)
                                                                .parent()
                                                                .children()
                                                                .prop(
                                                                    "disabled",
                                                                    true
                                                                );
                                                        }
                                                    }
                                                );
                                            }
                                        });
                                    }
                                );
                            }
                        },
                    });
                }
            }
        }
    }
}

var selectedDate;
var intervals;
var alreadyCreateTimeSlot;

let dateSelectSlot;
Lang.setLocale($(".userCurrentLanguage").val());
$("#patientId").first().focus();

var doctor = $("#doctor").val();

let appointmentDate = $("#appointmentDate").val();

var doctorId;
let doctorChange = false;

listenChange("#webDepartmentId", function () {
    $(".error-message").css("display", "none");
    var selectize = $("#appointmentDoctorId")[0].selectize;
    selectize.clearOptions();
    $("#opdDate").val("");
    // opdDate.clear();
    $.ajax({
        url: $("#homeDoctorDepartmentUrl").val(),
        type: "get",
        dataType: "json",
        data: { id: $(this).val() },
        success: function (data) {
            $("#appointmentDoctorId").empty();
            $("#appointmentDoctorId").append(
                $('<option value="">Select Doctor</option>')
            );
            $.each(data.data, function (i, v) {
                $("#appointmentDoctorId").append(
                    $("<option></option>").attr("value", i).text(v)
                );
            });
            let $select = $(
                document.getElementById("appointmentDoctorId")
            ).selectize();
            let selectize = $select[0].selectize;
            $.each(data.data, function (i, v) {
                selectize.addOption({ value: i, text: v });
            });
            selectize.refreshOptions();
        },
    });
});

listenChange("#appointmentDoctorId", function () {
    if (doctorChange) {
        $(".error-message").css("display", "none");
        $("#opdDate").val("");
        // opdDate.clear();
        $(".doctor-schedule").css("display", "none");
        $(".error-message").css("display", "none");
        $(".available-slot-heading").css("display", "none");
        $(".color-information").css("display", "none");
        $(".time-slot").remove();
        doctorChange = true;
    }
    $(".error-message").css("display", "none");
    doctorId = $(this).val();
    doctorChange = true;
});

//parseIn date_time
function parseIn(date_time) {
    var d = new Date();
    d.setHours(date_time.substring(16, 18));
    d.setMinutes(date_time.substring(19, 21));

    return d;
}

//make time slot list
function getTimeIntervals(time1, time2, duration) {
    var arr = [];
    if (
        new Date() > $("#opdDate").datepicker("getDate") &&
        new Date().getTime() > time2.getTime()
    ) {
        return arr;
    } else if (
        new Date() > new Date($("#appointmentDate").val()) &&
        new Date().getTime() > time2.getTime()
    ) {
        return arr;
    } else {
        while (time1 < time2) {
            arr.push(time1.toTimeString().substring(0, 5));
            time1.setMinutes(time1.getMinutes() + duration);
        }
        return arr;
    }
}

//slot click change color
var selectedTime;
listenClick(".time-interval", function (event) {
    let appointmentId = $(event.currentTarget).attr("data-id");
    if ($(this).data("id") == appointmentId) {
        if ($(this).parent().hasClass("booked")) {
            $(".time-slot-book").css("background-color", "#ffa0a0");
        }
    }
    selectedTime = $(this).text();
    $(".time-slot").removeClass("time-slot-book");
    $(this).parent().addClass("time-slot-book");
});

var editTimeSlot;
listenClick(".time-interval", function () {
    editTimeSlot = $(this).text();
});

let oldPatient = false;
listenChange(".new-patient-radio", function () {
    // loadAppointmentDate();
    if ($(this).is(":checked")) {
        $(".old-patient").addClass("d-none");
        $(".first-name-div").removeClass("d-none");
        $(".last-name-div").removeClass("d-none");
        $(".gender-div").removeClass("d-none");
        $(".password-div").removeClass("d-none");
        $(".confirm-password-div").removeClass("d-none");
        $(".appointment-slot").removeClass("d-none");
        $("#firstName").prop("required", true);
        $("#lastName").prop("required", true);
        $("#password").prop("required", true);
        $("#confirmPassword").prop("required", true);
        oldPatient = false;
    }
});
// console.log($('.old-patient-radio').val())
listenChange(".old-patient-radio", function () {
    // console.log('radio button change')
    if ($(this).is(":checked")) {
        $(".old-patient").removeClass("d-none");
        $(".first-name-div").addClass("d-none");
        $(".last-name-div").addClass("d-none");
        $(".gender-div").addClass("d-none");
        $(".password-div").addClass("d-none");
        $(".confirm-password-div").addClass("d-none");
        $(".appointment-slot").removeClass("d-none");
        $("#firstName").prop("required", false);
        $("#lastName").prop("required", false);
        $("#password").prop("required", false);
        $("#confirmPassword").prop("required", false);
        oldPatient = true;
    }
});

// function showScreenLoader () {
//     $('#overlay-screen-lock').removeClass('d-none');
// }
//
// function hideScreenLoader () {
//     $('#overlay-screen-lock').addClass('d-none');
// }

// listen('keypress', '#firstName, #lastName', function (e) {
//     if (e.which === 32)
//         return false;
// });

$.ajax({
    url: $("#homeDoctorUrl").val(),
    type: "get",
    dataType: "json",
    data: { id: doctor },
    success: function (data) {
        $("#appointmentDoctorId").empty();
        let $select = $(
            document.getElementById("appointmentDoctorId")
        ).selectize();
        let selectize = $select[0].selectize;
        $.each(data.data, function (i, v) {
            selectize.addOption({ value: i, text: v });
            selectize.setValue(i);
        });
    },
});

listenChange("#appointmentDoctorId", function () {
    $("#appointmentCharge").empty();
    $.ajax({
        url: $(".webDoctorChargeUrl").val(),
        type: "get",
        dataType: "json",
        data: { id: $(this).val() },
        success: function (data) {
            if (data.data != 0 && data.data != null && data.data != "") {
                $(".web-appointment-charge").removeClass("d-none");
                $("#appointmentCharge").val(data.data);
                $(".paymentType").removeClass("d-none");
                $(".paymentMode").removeClass("d-none");
                $("#appointmentPaymentModeId").prop("required", true);
            }
            if (data.data == 0 || data.data == undefined) {
                $(".web-appointment-charge").addClass("d-none");
                $("#appointmentCharge").val("");
                $(".paymentType").addClass("d-none");
            }
        },
    });
});

function disableSubmitButton() {
    $("#webAppointmentBtnSave").prop("disabled", true);
}

function enableSubmitButton() {
    $("#webAppointmentBtnSave").prop("disabled", false);
}

function formReset() {
    $(".old-patient").addClass("d-none");
    $(".first-name-div").removeClass("d-none");
    $(".last-name-div").removeClass("d-none");
    $(".gender-div").removeClass("d-none");
    $(".password-div").removeClass("d-none");
    $(".confirm-password-div").removeClass("d-none");
    $(".appointment-slot").removeClass("d-none");
    $("#firstName").prop("required", true);
    $("#lastName").prop("required", true);
    $("#password").prop("required", true);
    $("#confirmPassword").prop("required", true);
}

//create appointment
listenSubmit("#webAppointmentFormSubmit", function (event) {
    event.preventDefault();

    if (!oldPatient) {
        let isValidate = validatePassword();
        if (!isValidate) {
            return false;
        }
    }
    var isValid = true;
    $(".dynamic-field").each(function () {
        var fieldValue = $(this).val();
        var fieldLabel = $(this)
            .closest(".appointment-form__input-block")
            .find("label")
            .text()
            .replace(":", "")
            .trim();
        if (
            $(this).is(':input[type="text"], :input[type="number"], textarea')
        ) {
            if (!fieldValue || fieldValue.trim() === "") {
                displayErrorMessage(fieldLabel + ' ' + Lang.get('js.field_required'));
                isValid = false;
                enableSubmitButton()
                return false;
            }
        } else if ($(this).is(':input[type="toggle"]')) {
            if (!$(this).is(":checked")) {
                displayErrorMessage(fieldLabel + ' ' + Lang.get('js.field_required'));
                isValid = false;
                enableSubmitButton()
                return false;
            }
        } else if ($(this).is("select")) {
            if (
                !fieldValue &&
                $(this).val().length === 0 &&
                fieldValue.trim() === ""
            ) {
                displayErrorMessage(fieldLabel + ' ' + Lang.get('js.field_required'));
                isValid = false;
                enableSubmitButton()
                return false;
            }
        }
    });
    if (selectedTime == null || selectedTime === "") {
        displayErrorMessage(Lang.get("js.please_select_appointment_time_slot"));
        enableSubmitButton()
        return false;
    }

    $("#opdDate").val(
        moment($("#opdDate").datepicker("getDate")).format("MM/DD/YYYY")
    );
    disableSubmitButton();
    let formData = $(this).serialize() + "&time=" + selectedTime;
    $.ajax({
        url: $("#homeAppointmentSaveUrl").val(),
        type: "POST",
        dataType: "json",
        data: formData,
        success: function (result) {
            if (result.data == null) {
                displaySuccessMessage(
                    Lang.get("js.appointment") + " " + Lang.get("js.saved_successfully")
                );
                setTimeout(function () {
                    window.location.href = $('.backUrl').val()
                }, 5000);
            } else {
                if (result.data && result.data.payment_type) {
                    if (result.data.payment_type == 3) {
                        let sessionId = result.data[0].sessionId;
                        stripe
                            .redirectToCheckout({
                                sessionId: sessionId,
                            })
                            .then((mainResult) => manageAjaxErrors(mainResult));
                    } else if (result.data.payment_type == "4") {
                        let appId = result.data.appointment_id;
                        let formData =
                            $("#webAppointmentFormSubmit").serialize() +
                            "&appointment_id=" +
                            appId;
                        $.ajax({
                            url: $("#webAppointmentRazorpayInit").val(),
                            type: "POST",
                            data: formData,
                            processData: false,
                            success: function (data) {
                                if (data.success) {
                                    options.order_id = data.data.id;
                                    options.appointment_id =
                                        data.data.appointment_id;
                                    options.amount = data.data.amount;
                                    options.payment_mode = data.data.payment_mode;
                                    let rzp = new Razorpay(options);
                                    rzp.open();
                                }
                            },
                            error: function (error) {
                                displayErrorMessage(error.responseJSON.message);
                                setTimeout(function () {
                                    window.location.href = $(
                                        ".webAppointmentIndexPage"
                                    ).val();
                                }, 2000);
                            },
                        });
                    } else if (result.data.payment_type == "5") {
                        $.ajax({
                            type: "GET",
                            url: $("#webAppointmentPaypal").val(),
                            data: {
                                appointment_id: result.data.appointment_id,
                                payment_type: result.data.payment_type,
                                amount: result.data.amount,
                            },
                            success: function (data) {
                                if (data.url) {
                                    window.location.href = data.url;
                                }
                            },
                            error: function (data) {
                                displayErrorMessage(data.responseJSON.message);
                            },
                            complete: function () { },
                        });
                    } else if (result.data.payment_type == 8) {
                        if (result.data.url != null) {
                            window.location.href = result.data.url;
                        }
                    } else if (result.data.payment_type == 7) {
                        if (result.data.url != null) {
                            window.location.href = result.data.url;
                        }
                    }
                } else if (result.data.payStackData != null) {
                    if (result.data.payStackData.payment_type == 9) {
                        window.location.replace(route("appointment.paystack.init", { data: result.data.payStackData }));
                    }
                }
            }
        },
        error: function (result) {
            // console.log(result.responseJSON.message)
            displayErrorMessage(Lang.get("js.old_patient_email_exists"));
            $("#webAppointmentFormSubmit")[0].reset();
            $(".appointment-slot").addClass("d-none");
        },
    });
});

function validatePassword() {
    let password = $("#password").val();
    let confirmPassword = $("#confirmPassword").val();

    if (password == "" || confirmPassword == "") {
        displayErrorMessage(Lang.get("js.please_fill_all_the_required_fields"));
        enableSubmitButton()
        return false;
    }

    if (password !== confirmPassword) {
        displayErrorMessage(
            Lang.get("js.password_and_confirm_password_not_match")
        );
        enableSubmitButton()
        return false;
    }

    return true;
}
