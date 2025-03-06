"use strict";

// document.addEventListener("DOMContentLoaded", loadAppointmentTable);

var appointmentStart = moment().startOf("week");
var appointmentEnd = moment().endOf("week");

Livewire.hook("element.init", ({ component }) => {
    if (component.name == 'appointment-table') {
        loadAppointmentTable();
    }
});

function loadAppointmentTable() {
    let appointmentTimeRange = $("#time_range");
    let appointmentStartTime = "";
    let appointmentEndTime = "";

    if ($("#appointmentStatus").length) {
        $("#appointmentStatus").select2();
    }

    if (appointmentStart != undefined && appointmentEnd != undefined) {
        cb(appointmentStart, appointmentEnd);
    }

    if (appointmentTimeRange.length) {
        Lang.setLocale($(".userCurrentLanguage").val());
        appointmentTimeRange.daterangepicker(
            {
                startDate: appointmentStart,
                endDate: appointmentEnd,
                locale: {
                    customRangeLabel: Lang.get("js.custom"),
                    applyLabel: Lang.get("js.apply"),
                    cancelLabel: Lang.get("js.cancel"),
                    fromLabel: Lang.get("js.from"),
                    toLabel: Lang.get("js.to"),
                    monthNames: [
                        Lang.get("js.jan"),
                        Lang.get("js.feb"),
                        Lang.get("js.mar"),
                        Lang.get("js.apr"),
                        Lang.get("js.may"),
                        Lang.get("js.jun"),
                        Lang.get("js.july"),
                        Lang.get("js.aug"),
                        Lang.get("js.sep"),
                        Lang.get("js.oct"),
                        Lang.get("js.nov"),
                        Lang.get("js.dec"),
                    ],
                    daysOfWeek: [
                        Lang.get("js.sun"),
                        Lang.get("js.mon"),
                        Lang.get("js.tue"),
                        Lang.get("js.wed"),
                        Lang.get("js.thu"),
                        Lang.get("js.fri"),
                        Lang.get("js.sat"),
                    ],
                },
                ranges: {
                    [Lang.get("js.today")]: [moment(), moment()],
                    [Lang.get("js.yesterday")]: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    [Lang.get("js.this_week")]: [
                        moment().startOf("week"),
                        moment().endOf("week"),
                    ],
                    [Lang.get("js.last_7_days")]: [
                        moment().subtract(6, "days"),
                        moment(),
                    ],
                    [Lang.get("js.last_30_days")]: [
                        moment().subtract(29, "days"),
                        moment(),
                    ],
                    [Lang.get("js.this_month")]: [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    [Lang.get("js.last_month")]: [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            }, cb)
        cb(appointmentStart, appointmentEnd)
        appointmentTimeRange.on("apply.daterangepicker", function (ev, picker) {
            appointmentStartTime =
                picker.startDate.format("YYYY-MM-D  H:mm:ss");
            appointmentEndTime = picker.endDate.format("YYYY-MM-D  H:mm:ss")
            Livewire.dispatch("changeDateFilter", {
                dateFilter: [appointmentStartTime, appointmentEndTime]
            });
            appointmentStart = picker.startDate;
            appointmentEnd = picker.endDate;
        })

    }

    function cb(startDate, endDate) {
        $('#time_range').
            html(startDate.format('MMM D, YYYY') + ' - ' +
                endDate.format('MMM D, YYYY'))
    }
    // listenClick('.appointment-delete-btn', function (event) {
    //     let appointmentId = $(event.currentTarget).attr('data-id');
    //     deleteItem($('.appointmentURL').val() + '/' + appointmentId,
    //         '#appointmentsTbl',
    //         $('#appointmentLang').val())
    // })

    listenChange('#appointmentStatus', function () {
        let status = $(this).val();
        Livewire.dispatch('changeFilter', { statusFilter: [appointmentStartTime, appointmentEndTime, status] })
    })

    listenClick('#appointmentResetFilter', function () {
        let appointmentTimeRange = $('#time_range')
        appointmentStartTime = appointmentTimeRange.data('daterangepicker').
            setStartDate(moment().startOf('week').format('MM/DD/YYYY'))
        appointmentEndTime = appointmentTimeRange.data('daterangepicker').
            setEndDate(moment().endOf('week').format('MM/DD/YYYY'))
        Livewire.dispatch('changeFilter', { statusFilter: [appointmentStartTime, appointmentEndTime, 2] })
        $('#appointmentStatus').val(2);
        hideDropdownManually($('#appointmentFilterBtn'),
            $('.dropdown-menu'))
    });

    listenClick(".appointment-complete-status", function (event) {
        let appointmentId = $(event.currentTarget).attr("data-id");
        completeAppointment(
            $(".appointmentURL").val() + "/" + appointmentId + "/status",
            "#appointmentsTbl",
            Lang.get("js.appointment") + " " + Lang.get("js.status")
        );
    });

    listenClick(".cancel-appointment", function () {
        let appointmentId = $(this).attr("data-id");
        cancelAppointment(
            $(".appointmentURL").val() + "/" + appointmentId + "/cancel",
            "",
            Lang.get("js.appointment")
        );
    });

    function completeAppointment(url, tableId, header, appointmentId) {
        swal({
            title: Lang.get("js.change_status"),
            text:
                Lang.get("js.are_you_sure_want_to_change") +
                " " +
                header +
                " ?",
            type: "warning",
            icon: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonColor: "#50cd89",
            showLoaderOnConfirm: true,
            buttons: {
                confirm: Lang.get("js.yes"),
                cancel: Lang.get("js.no"),
            },
        }).then(function (result) {
            if (result) {
                completeAppointmentAjax(url, tableId, header, appointmentId);
            }
        });
    }

    function completeAppointmentAjax(url, tableId, header, appointmentId) {
        $.ajax({
            url: url,
            type: "POST",
            success: function (obj) {
                if (obj.success) {
                    Livewire.dispatch("refresh");
                }
                swal({
                    title: Lang.get("js.changed_appointment"),
                    text: header + " " + Lang.get("js.has_been_changed"),
                    icon: "success",
                    confirmButtonColor: "#50cd89",
                    buttons: {
                        confirm: Lang.get("js.ok"),
                    },
                    timer: 2000,
                });
            },
            error: function (data) {
                swal({
                    title: "Error",
                    icon: "error",
                    text: data.responseJSON.message,
                    type: "error",
                    confirmButtonColor: "#50cd89",
                    buttons: {
                        confirm: Lang.get("js.ok"),
                    },
                    timer: 5000,
                });
            },
        });
    }
}
