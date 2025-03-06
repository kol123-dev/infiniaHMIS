document.addEventListener('DOMContentLoaded', loadDoctorHolidayDetails)

function loadDoctorHolidayDetails() {

    let lang = $(".userCurrentLanguage").val();

    $('#doctorHolidayDate').flatpickr({
        'locale': lang,
        minDate: new Date().fp_incr(1),
        disableMobile: true,
        position: lang == 'ar' ? 'auto right' : '',
    })
}

listenClick('.holiday-delete-btn', function (event) {
    let holidayRecordId = $(event.currentTarget).attr('data-id')
    deleteItem(route('doctors.holiday-destroy', holidayRecordId), Lang.get('js.holiday'))
});
