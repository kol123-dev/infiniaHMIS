// document.addEventListener('DOMContentLoaded', loadDoctorHoliday)

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'doctor-holiday-table'){
        if(component.canonical.dateFilter.length == 0){
            loadDoctorHoliday ();
        }
    }
});

function loadDoctorHoliday () {
    loadHoliday()

    listenClick('.doctor-holiday-delete-btn', function (event) {
        let holidayRecordId = $(event.currentTarget).attr('data-id')
        deleteItem(route('holidays.destroy', holidayRecordId),'', Lang.get('js.holiday'))
    })

    if (!$('#doctorHolidayDateFilter').length) {
        return
    }

    let startDate = moment().startOf('week')
    let endDate = moment().endOf('week')

    function cb (start, end) {
        $('#doctorHolidayDateFilter').val(
            start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'))
    }

    $('#doctorHolidayDateFilter').daterangepicker({
        startDate: startDate,
        endDate: endDate,
        opens: 'left',
        showDropdowns: true,
        locale: {
            customRangeLabel: Lang.get('js.custom'),
            applyLabel:Lang.get('js.apply'),
            cancelLabel: Lang.get('js.cancel'),
            fromLabel:Lang.get('js.from'),
            toLabel: Lang.get('js.to'),
            monthNames: [
                Lang.get('js.jan'),
                Lang.get('js.feb'),
                Lang.get('js.mar'),
                Lang.get('js.apr'),
                Lang.get('js.may'),
                Lang.get('js.jun'),
                Lang.get('js.july'),
                Lang.get('js.aug'),
                Lang.get('js.sep'),
                Lang.get('js.oct'),
                Lang.get('js.nov'),
                Lang.get('js.dec')
            ],
            daysOfWeek: [
                Lang.get('js.sun'),
                Lang.get('js.mon'),
                Lang.get('js.tue'),
                Lang.get('js.wed'),
                Lang.get('js.thu'),
                Lang.get('js.fri'),
                Lang.get('js.sat')
            ],
        },
        ranges: {
            [ Lang.get('js.today')]: [moment(), moment()],
            [ Lang.get('js.yesterday')]: [
                moment().subtract(1, 'days'),
                moment().subtract(1, 'days')],
            [ Lang.get('js.this_week')]: [moment().startOf('week'), moment().endOf('week')],
            [ Lang.get('js.last_7_days')]: [moment().subtract(6, 'days'), moment()],
            [ Lang.get('js.last_30_days')]: [moment().subtract(29, 'days'), moment()],
            [ Lang.get('js.this_month')]: [
                moment().startOf('month'),
                moment().endOf('month')],
            [ Lang.get('js.last_month')]: [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')],
        },
    }, cb)

    cb(startDate, endDate)
}

listenChange('#doctorHolidayStatus', function () {
    $('#doctorHolidayStatus').val($(this).val())
    Livewire.dispatch('changeStatusFilter', $(this).val())
})

function loadHoliday () {
    if (!$('#holidayDateFilter').length) {
        return
    }

    let Start = moment().startOf('week')
    let End = moment().endOf('week')

    function cb (start, end) {
        $('#holidayDateFilter').val(
            start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'))
    }

    $('#holidayDateFilter').daterangepicker({
        startDate: Start,
        endDate: End,
        opens: 'left',
        showDropdowns: true,
        locale: {
            customRangeLabel: Lang.get('js.custom'),
            applyLabel:Lang.get('js.apply'),
            cancelLabel: Lang.get('js.cancel'),
            fromLabel:Lang.get('js.from'),
            toLabel: Lang.get('js.to'),
            monthNames: [
                Lang.get('js.jan'),
                Lang.get('js.feb'),
                Lang.get('js.mar'),
                Lang.get('js.apr'),
                Lang.get('js.may'),
                Lang.get('js.jun'),
                Lang.get('js.july'),
                Lang.get('js.aug'),
                Lang.get('js.sep'),
                Lang.get('js.oct'),
                Lang.get('js.nov'),
                Lang.get('js.dec')
            ],

            daysOfWeek: [
                Lang.get('js.sun'),
                Lang.get('js.mon'),
                Lang.get('js.tue'),
                Lang.get('js.wed'),
                Lang.get('js.thu'),
                Lang.get('js.fri'),
                Lang.get('js.sat')],
        },
        ranges: {
            [Lang.get('js.today')]: [moment(), moment()],
            [Lang.get('js.yesterday')]: [
                moment().subtract(1, 'days'),
                moment().subtract(1, 'days')],
            [Lang.get('js.this_week')]: [moment().startOf('week'), moment().endOf('week')],
            [Lang.get('js.last_30_days')]: [moment().subtract(29, 'days'), moment()],
            [Lang.get('js.this_month')]: [moment().startOf('month'), moment().endOf('month')],
            [Lang.get('js.last_month')]: [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')],
        },
    }, cb)

    cb(Start, End)

}

listenChange('#holidayDateFilter,#doctorHolidayDateFilter', function () {
    Livewire.dispatch('changeDateFilter', {dateFilter: $(this).val()})
});

listenClick('#holidayDateResetFilter', function () {
    $('#holidayDateFilter').data('daterangepicker').setStartDate(moment().startOf('week').format('MM/DD/YYYY'))
    $('#holidayDateFilter').data('daterangepicker').setEndDate(moment().endOf('week').format('MM/DD/YYYY'))
    hideDropdownManually($('#holidayFilterBtn'), $('.dropdown-menu'));
});

listenClick('#doctorHolidayResetFilter', function () {
    $('#doctorHolidayDateFilter').data('daterangepicker').setStartDate(moment().startOf('week').format('MM/DD/YYYY'))
    $('#doctorHolidayDateFilter').data('daterangepicker').setEndDate(moment().endOf('week').format('MM/DD/YYYY'))
    hideDropdownManually($('#doctorHolidayFilterBtn'), $('.dropdown-menu'));
});
