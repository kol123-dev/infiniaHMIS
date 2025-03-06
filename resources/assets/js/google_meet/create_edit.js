"use strict";

listenSubmit("#googleCalendarForm", function (e) {
    e.preventDefault()

    if (!$('.google-calendar').is(':checked')) {
        displayErrorMessage(Lang.get('js.select_calendar'));
        return
    }

    $.ajax({
        url: route('event.google.calendar.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                setTimeout(function () {
                    location.reload()
                }, 1200)
            }
        },
        error: function (error) {
            displayErrorMessage(error.responseJSON.message)
        },
    })
});


listenClick('#syncGoogleCalendar', function () {

    if (!$('.google-calendar').is(':checked')) {
        displayErrorMessage(Lang.get('js.select_calendar'));
        return
    }

    $.ajax({
        url: route('syncGoogleCalendarList'),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message)
                setTimeout(function () {
                    location.reload()
                }, 1200)
            }
        },
        error: function (result) {
            if(result.status == 401){
                displayErrorMessage(Lang.get('js.disconnect_or_reconnect'));
            }else{
                displayErrorMessage(result.responseJSON.message)
            }
        },
    })
})

listenChange('.google_json_file', function(){
    $("#jsonFileImage").css("background-image","url('assets/img/json.png')");
});
