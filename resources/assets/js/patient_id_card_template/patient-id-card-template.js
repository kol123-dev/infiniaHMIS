listenClick('.delete-patient-card-btn', function (event) {
    let patientIdCardTemplateId = $(event.currentTarget).data('id');
    deleteItem(route("smart-patient-cards.destroy", patientIdCardTemplateId), ' ', Lang.get("js.patient_id_card_template"));
});
