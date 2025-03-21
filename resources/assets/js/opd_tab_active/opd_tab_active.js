document.addEventListener('DOMContentLoaded', loadOpdTabActiveData)

function loadOpdTabActiveData() {
    if (!$('#OPDtab').length) {
        return
    }
    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#OPDtab a[href="' + hash + '"]').tab('show');
}
listenClick('#OPDtab a', function (e) {
    e.preventDefault();
    $(this).tab('show');
});
// store the currently selected tab in the hash value
$('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
    var id = $(e.target).attr('href').substr(1);
    window.location.hash = id;
});
