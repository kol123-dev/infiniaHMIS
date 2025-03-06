Livewire.hook('element.init', ({component}) => {
    if(component.name == 'user-table'){
        $("#usersStatusArr, #userRoleArr").select2({
            width: "100%",
        });
    }
});

listen("click", ".user-delete-btn", function (event) {
    let userId = $(event.currentTarget).attr("data-id");
    deleteItem(
        $("#indexUserUrl").val() + "/" + userId,
        "",
        Lang.get("js.user")
    );
});

listen("change", ".user-status", function (event) {
    let userId = $(event.currentTarget).attr("data-id");
    updateUserStatus(userId);
});

listen("click", ".show-user-btn", function (event) {
    event.preventDefault();
    let userId = $(event.currentTarget).attr("data-id");
    renderUserData(userId);
});

function renderUserData(id) {
    $.ajax({
        url: $("#usersShowModal").val() + "/" + id,
        type: "GET",
        success: function (result) {
            if (result.success) {
                $("#showUserFirst_name").text(result.data.first_name);
                $("#showUserLast_name").text(result.data.last_name);
                $("#showUserEmail").text(result.data.email);
                $("#showUserRole").text(result.data.roles[0].name);
                $("#showUserPhone").text(
                    result.data.phone ?? Lang.get("js.n/a")
                );
                $("#showUserGender").text(result.data.gender_string);
                $("#showUserDob").text("");
                if (result.data.dob != null)
                    $("#showUserDob").text(
                        moment(result.data.dob).format("Mo MMM, YYYY")
                    );
                $("#showUserStatus").empty();
                let active = $("#userActive").val();
                let deActive = $("#userDeActive").val();
                if (result.data.status == 1) {
                    $("#showUserStatus").append(
                        '<span class="badge bg-light-success">' +
                            active +
                            "</span>"
                    );
                } else {
                    $("#showUserStatus").append(
                        '<span class="badge bg-light-danger">' +
                            deActive +
                            "</span>"
                    );
                }
                moment.locale($("#userLanguage").val());
                $("#showUserCreated_on").text(
                    moment(result.data.created_at).fromNow()
                );
                $("#showUserUpdated_on").text(
                    moment(result.data.updated_at).fromNow()
                );
                $("#showUserProfilePicture").attr("src", result.data.image_url);

                setValueOfEmptySpan();
                $("#showUser").appendTo("body").modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

function updateUserStatus(id) {
    $.ajax({
        url: $("#indexUserUrl").val() + "/" + id + "/active-deactive",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
    });
}

listen("change", ".is-user-verified", function (event) {
    let userId = $(event.currentTarget).attr("data-id");
    $.ajax({
        url: $("#indexUserUrl").val() + "/" + userId + "/is-verified",
        method: "post",
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
            }
        },
    });
});

// listenChange("#usersStatusArr", function () {
//     Livewire.dispatch("changeFilter", "statusFilter", $(this).val());
// });

listenChange("#usersStatusArr", function() {
    Livewire.dispatch("changeFilter",{ statusFilter: $(this).val() });
});

listenChange("#userRoleArr", function () {
    Livewire.dispatch("changeRoleFilter", { roleFilter: $(this).val()});
});

listenClick("#userResetFilter", function () {
    $("#userRoleArr").val(0).trigger("change");
    $("#usersStatusArr").val(0).trigger("change");
    hideDropdownManually($("#userFilterButton"), $(".dropdown-menu"));
});
