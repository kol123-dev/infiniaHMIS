// document.addEventListener('DOMContentLoaded', loadSuperAdminDashboardData)

Livewire.hook("element.init", () => {
    loadSuperAdminDashboardData();
});

let incomeChartCanvasAttr = "";
let dashboardPlanIncomeChartData = "";

function loadSuperAdminDashboardData() {
    if (!$("#incomeExpenseChart").length) {
        return;
    }

    incomeChartCanvasAttr = $("#incomeExpenseChart");
    dashboardChart();
}

const dashboardChart = () => {
    $.ajax({
        type: "post",
        url: route("dashboard.chart"),
        dataType: "json",
        success: function (result) {
            incomeChartCanvasAttr.empty();
            dashboardPlanIncomeChartData = result.data;
            dashboardPlanIncomeChart(dashboardPlanIncomeChartData);
        },
        cache: false,
    });
};

const dashboardPlanIncomeChart = (data) => {
    if (window.myChart != null){
        window.myChart.destroy();
        window.myChart = null;
    }

    var ctx = document.getElementById("incomeExpenseChart").getContext("2d");

    window.myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: data.days,
            datasets: [
                {
                    label: Lang.get("js.income"),
                    data: data.income.data,
                    fill: false,
                    borderColor: "rgb(153, 102, 255)",
                    backgroundColor: "rgba(153, 102, 255, 0.2)",
                    borderWidth: 2,
                },
                {
                    label: Lang.get("js.expenses"),
                    data: data.expense.data,
                    fill: false,
                    borderColor: "rgb(43,116,216)",
                    backgroundColor: "rgba(92,123,233,0.2)",
                    borderWidth: 2,
                },
            ],
        },
        options: {
            layout: {
                padding: {
                    bottom: 30,
                    top: 20,
                },
            },
            elements: {
                line: {
                    tension: 0.5,
                },
            },
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text:
                        Lang.get("js.yearly_income_expense_chart") +
                        " " +
                        "(" +
                        moment().year() +
                        ")",
                    align: "start",
                    font: {
                        size: "20px",
                        lineHeight: 1.2,
                    },
                },
                legend: {
                    display: true,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: Lang.get("js.amount"),
                    },
                    grid: {
                        display: false,
                    },
                    ticks: {
                        min: 0,
                        callback: function (value) {
                            return new Intl.NumberFormat("en-US", {
                                style: "currency",
                                currency: getCurrentCurrency(),
                            }).format(value);
                        },
                    },
                },
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: Lang.get("js.month"),
                    },
                    grid: {
                        display: false,
                    },
                },
            },
        },
    });
};

listenClick(".notice-board-view-btn", function (event) {
    if ($(".ajaxCallIsRunning").val()) {
        return;
    }
    ajaxCallInProgress();
    let noticeBoardId = $(event.currentTarget).attr("data-id");
    $.ajax({
        url: $(".noticeBoardUrl").val() + "/" + noticeBoardId,
        type: "GET",
        success: function (result) {
            if (result.success) {
                $("#showNoticeBoardTitle").html("");
                $("#showNoticeBoardDescription").html("");
                $("#showNoticeBoardTitle").append(result.data.title);
                $("#showNoticeBoardDescription").append(
                    result.data.description
                );
                $("#show_notice_boards_modal").appendTo("body").modal("show");
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });
});
