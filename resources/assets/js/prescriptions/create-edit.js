document.addEventListener("DOMContentLoaded", loadPrescriptionCreate);

let uniquePrescriptionId = "";

function loadPrescriptionCreate() {
    if (
        !$("#prescriptionPatientId").length &&
        !$("#editPrescriptionPatientId").length
    ) {
        return;
    }
    const prescriptionAddedAtElement = $("#prescriptionAddedAt");
    const editPrescriptionAddedAtElement = $("#editPrescriptionAddedAt");
    $(
        "#prescriptionPatientId,#editPrescriptionPatientId,#filter_status,#prescriptionDoctorId,#editPrescriptionDoctorId,#prescriptionTime,#prescriptionMedicineCategoryId,#prescriptionMedicineBrandId,.prescriptionMedicineId,.prescriptionMedicineMealId,#editPrescriptionTime,.prescriptionMedicineDurationId,.prescriptionMedicineIntervalId"
    ).select2({
        width: "100%",
    });

    $("#prescriptionMedicineBrandId, #prescriptionMedicineBrandId").select2({
        width: "100%",
        dropdownParent: $("#add_new_medicine"),
    });
    if (prescriptionAddedAtElement.length) {
        $("#prescriptionAddedAt").flatpickr({
            maxDate: new Date(),
            locale: $(".userCurrentLanguage").val(),
        });
    }
    if (editPrescriptionAddedAtElement.length) {
        $("#editPrescriptionAddedAt").flatpickr({
            maxDate: new Date(),
            locale: $(".userCurrentLanguage").val(),
        });
    }
    $("#prescriptionPatientId").first().focus();
}
listenSubmit("#createPrescription, #editPrescription", function () {
    $(".btnPrescriptionSave").attr("disabled", true);
});

listenSubmit("#createMedicineFromPrescription", function (e) {
    e.preventDefault();
    $.ajax({
        url: $("#createMedicineFromPrescriptionPost").val(),
        method: "POST",
        data: $(this).serialize(),
        success: function (result) {
            $(".medicineTable").load(
                location.href + " .medicineTable",
                function () {
                    $(".prescriptionMedicineId").select2();
                }
            );
            displaySuccessMessage(result.message);
            $("#add_new_medicine").modal("hide");
        },
        error: function (result) {
            printErrorMessage("#medicinePrescriptionErrorBox", result);
        },
    });
});

const dropdownToSelecte2 = (selector) => {
    $(selector).select2({
        placeholder: Lang.get("js.select_medicine"),
        width: "100%",
    });
};

listenHiddenBsModal("#add_new_medicine", function () {
    resetModalForm(
        "#createMedicineFromPrescription",
        "#medicinePrescriptionErrorBox"
    );
});

listenClick(".delete-prescription-medicine-item", function () {
    $(this).parents("tr").remove();
    // resetPrescriptionMedicineItemIndex()
});

listenClick(".add-medicine-btn", function () {
    let uniquePrescriptionId = $("#prescriptionUniqueId").val();
    var currentIndex = parseInt($(this).attr('data-index'));
    currentIndex++;

    $(this).attr('data-index', currentIndex);
    $('#prescriptionUniqueId').val(currentIndex);
    let data = {
        medicines: JSON.parse($(".associatePrescriptionMedicines").val()),
        meals: JSON.parse($(".associatePrescriptionMeals").val()),
        doseDuration: JSON.parse($(".associatePrescriptionDurations").val()),
        doseInterval: JSON.parse($(".associatePrescriptionIntervals").val()),
        uniqueId: uniquePrescriptionId,
    };
    let prescriptionMedicineHtml = prepareTemplateRender(
        "#prescriptionMedicineTemplate",
        data
    );
    $(".prescription-medicine-container").append(prescriptionMedicineHtml);
    dropdownToSelecte2(".prescriptionMedicineId");
    dropdownToSelecte2(".prescriptionMedicineMealId");
    dropdownToSelecte2(".prescriptionMedicineDurationId");
    dropdownToSelecte2(".prescriptionMedicineIntervalId");
    uniquePrescriptionId++;
    $("#prescriptionUniqueId").val(uniquePrescriptionId);

    // resetPrescriptionMedicineItemIndex();
});

const resetPrescriptionMedicineItemIndex = () => {
    let index = 1;
    if (index - 1 == 0) {
        let uniquePrescriptionId = $("#prescriptionUniqueId").val();
        let data = {
            medicines: JSON.parse($(".associatePrescriptionMedicines").val()),
            meals: JSON.parse($(".associatePrescriptionMeals").val()),
            doseDuration: JSON.parse(
                $(".associatePrescriptionDurations").val()
            ),
            doseInterval: JSON.parse(
                $(".associatePrescriptionIntervals").val()
            ),
            uniqueId: uniquePrescriptionId,
        };
        let packageServiceItemHtml = prepareTemplateRender(
            "#prescriptionMedicineTemplate",
            data
        );
        $(".prescription-medicine-container").append(packageServiceItemHtml);
        dropdownToSelecte2(".prescriptionMedicineId");
        dropdownToSelecte2(".prescriptionMedicineMealId");
        dropdownToSelecte2(".prescriptionMedicineDurationId");
        dropdownToSelecte2(".prescriptionMedicineIntervalId");
        uniquePrescriptionId++;
        $("#prescriptionUniqueId").val(uniquePrescriptionId);
    }
};

listenChange(".prescriptionMedicineId", function () {
    let uniqueId = $(this).attr("data-id");
    let medicineId = $(this).val();
    let currentRow = $(this).closest("tr");
    let AvailbleQty = currentRow.find("#AvailbleQty:first");
    let AvailbleQtyClass = currentRow.find(".AvailbleQtyClass");
    let AvlQtyDiv = "#medicineDiv" + uniqueId;

    $.ajax({
        url: route("prescription.medicine.quantity", medicineId),
        type: "GET",
        success: function (data) {
            if (data.data.length !== 0) {
                let availableQuantity = data.data.available_quantity;
                let availbleQtyText = `${Lang.get(
                    "js.available_quantity"
                )}: ${availableQuantity}`;
                let availbleQtyClass =
                    availableQuantity == 0 ? "text-danger" : "text-success";

                $(AvailbleQtyClass).text("");

                $(AvailbleQty)
                    .text(availbleQtyText)
                    .removeClass()
                    .addClass(availbleQtyClass);
                $(AvlQtyDiv).css({ "margin-top": "22px" });
            }
        },
        error: function () {
            $(AvailbleQty).text("");
            $(AvlQtyDiv).css({ "margin-top": "0px" });
        },
    });
});



listenClick('#openAiPrompt', function (e) {
    e.preventDefault();
    let $btn = $(this);
    let originalButtonText = $btn.html();

    let highBloodPressure = $('#highBloodPressure').val();
    let foodAllergies = $('#foodAllergies').val();
    let tendencyBleed = $('#tendencyBleed').val();
    let diabetic = $('#diabetic').val();
    let heartDisease = $('#heartDisease').val();
    let femalePregnancy = $('#femalePregnancy').val();
    let breastFeeding = $('#breastFeeding').val();
    let currentMedication = $('#currentMedication').val();
    let surgery = $('#surgery').val();
    let accident = $('#accident').val();
    let others = $('#others').val();
    let plusRate = $('#plusRate').val();
    let temperature = $('#temperature').val();
    let problemDescription = $('#problemDescription').val();

    if (!highBloodPressure &&
        !foodAllergies &&
        !diabetic &&
        !tendencyBleed &&
        !heartDisease &&
        !femalePregnancy &&
        !breastFeeding &&
        !currentMedication &&
        !surgery &&
        !accident &&
        !others &&
        !plusRate &&
        !temperature &&
        !problemDescription
    ) {
        displayErrorMessage(Lang.get('js.fill_physical_info'));
        return;
    }

    let data = {
        highBloodPressure: highBloodPressure,
        diabetic: diabetic,
        foodAllergies: foodAllergies,
        tendencyBleed: tendencyBleed,
        heartDisease: heartDisease,
        femalePregnancy: femalePregnancy,
        breastFeeding: breastFeeding,
        currentMedication: currentMedication,
        surgery: surgery,
        accident: accident,
        others: others,
        plusRate: plusRate,
        temperature: temperature,
        problemDescription: problemDescription
    };

    $btn.html($btn.data('loading-text')).prop('disabled', true);

    $.ajax({
        url: route('prescription.open-ai-prompt'),
        type: 'POST',
        data: data,
        success: function (result) {
            if (result.success) {
                let medicines = result.data.medicines

                let suggestionBox = $('.suggestion-box');
                suggestionBox.empty();

                medicines.forEach(function (medicine, index) {
                    let medicineHtml = `
                        <div class="medicine-item">
                            <h5>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Medicine Name:</strong>
                                        <span id="medicine-name-${index}">${medicine["Real Medicine Name"]}</span>
                                    </div>
                                    <button class="btn btn-primary btn-sm copy-medicine-btn" data-clipboard-target="#medicine-name-${index}" title="Copy">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </h5>
                            <p class="mb-2"><strong>Dosage:</strong> ${medicine["Dosage"]}</p>
                            <p class="mb-2"><strong>Dose Duration:</strong> ${medicine["Dose Duration"]}</p>
                            <p class="mb-2"><strong>Dose Interval:</strong> ${medicine["Dose Interval"]}</p>
                            <p class="mb-2"><strong>Time:</strong> ${medicine["Time"]}</p>
                            <p class="mb-2"><strong>Comment:</strong> ${medicine["Comment"]}</p>
                        </div>
                        <hr>
                    `;
                    suggestionBox.append(medicineHtml);
                });

                new ClipboardJS('.copy-medicine-btn');

                $('#medicineSuggestModal').modal('show');

            }

            $btn.html(originalButtonText).prop('disabled', false);
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
            $btn.html(originalButtonText).prop('disabled', false);
        }
    });
});



listenHiddenBsModal('#add_prescription_prompt', function () {
    resetModalForm(
        '#opneAiPromptForm',
        '#opneAiPromptFormErrorsBox'
    );
});
