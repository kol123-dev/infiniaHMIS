'use strict';

listenClick('.deletePathologyTestBtn', function (event) {
    let pathologyTestId = $(event.currentTarget).attr('data-id');
    deleteItem($('#pathologyTestURL').val() + '/' + pathologyTestId, '',
        // Lang.get('messages.pathology_test.pathology_tests'));
        $('#pathologyTest').val());
});

listenClick('.showPathologyTestBtn', function (event) {
    event.preventDefault()
    let pathologyTestId = $(event.currentTarget).attr('data-id');
    renderPathologyTestData(pathologyTestId);
});

window.renderPathologyTestData = function (id) {
    $.ajax({
        url: $('#pathologyTestShowUrl').val() + '/' + id,
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#showPathologyTestName').text(result.data.test_name);
                $('#showPathologyTestShortName').text(result.data.short_name);
                $('#showPathologyTestType').text(result.data.test_type);
                $('#showPathologyCategories').text(result.data.pathology_category_name);
                $('#showPathologyTestUnit').text(result.data.unit);
                $('#showPathologyTestSubcategory').text(result.data.subcategory);
                $('#showPathologyTestMethod').text(result.data.method);
                $('#showPathologyTestReportDays').text(result.data.report_days);
                $('#showPathologyChargeCategories').text(result.data.charge_category_name);
                $('#showPTestStandardCharge').text(result.data.standard_charge);
                moment.locale($('#pathologyTestLanguage').val());
                $('#showPathologyTestCreatedOn').text(moment(result.data.created_at).fromNow());
                $('#showPathologyTestUpdatedOn').text(moment(result.data.updated_at).fromNow());

                setValueOfEmptySpan();
                $('#showPathologyTest').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
};

listenChange(".patholory-parameter-data", function () {
    let parameterId = $(this).val();
    let currentRow = $(this).closest("tr");
    let rangeId = currentRow.find("#rangeId");
    let unitId = currentRow.find("#unitId");

    if (parameterId == "") {
        $(rangeId).val("");
        $(unitId).val("");

        return false;
    }
    $.ajax({
        type: "get",
        url: route("get-pathology-parameter", parameterId),
        success: function (result) {
            $(rangeId).val(result.data.parameter.reference_range);
            $(unitId).val(result.data.parameter.pathology_unit.name);
        },
    });
});

listenClick(".add-parameter-test", function () {
    let uniqueParameterId = $("#parameterUniqueId").val();
    let data = {
        parameters: JSON.parse($(".associateParameters").val()),
        uniqueId: uniqueParameterId,
    };

    let pathologyParameterHtml = prepareTemplateRender(
        "#pathologyParameterTemplate",
        data
    );
    $(".pathology-test-container").append(pathologyParameterHtml);
    dropdownToSelecte2(".patholory-parameter-data");

    uniqueParameterId++;
});
const dropdownToSelecte2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get('js.select_parameter_name'),
        width: "100%",
    });
};

listenClick(".delete-parameter-test", function () {
    $(this).parents("tr").remove();
});
