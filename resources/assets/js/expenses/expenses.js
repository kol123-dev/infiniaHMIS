// document.addEventListener("DOMContentLoaded", loadExpense);

Livewire.hook("element.init", ({component}) => {
    if(component.name == 'expense-table'){
        loadExpense();

        $('#ExpenseHead').select2({
            width: '100%',
        });
    }
});

function loadExpense() {
    if (!$("#indexExpenseUrl").length) {
        return;
    }

    $("#expenseHead").select2({
        width: "100%",
    });
    $("#expenseId").select2({
        width: "100%",
        dropdownParent: $("#add_expenses_modal"),
    });
    $("#editExpenseHeadId").select2({
        width: "100%",
        dropdownParent: $("#edit_expenses_modal"),
    });
}

listenClick("#ExpenseResetFilter", function () {
    $("#ExpenseHead").val(0).trigger("change");
    hideDropdownManually($("#ExpenseFilterBtn"), $(".dropdown-menu"));
});
