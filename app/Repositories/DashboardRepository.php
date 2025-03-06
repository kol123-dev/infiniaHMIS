<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * Class DashboardRepository
 */
class DashboardRepository
{
    public function getIncomeExpenseReport($input)
    {
        $dates = $this->getDate($input['start_date'], $input['end_date']);

        $incomes = Income::all();
        $expenses = Expense::all();

        //Income report
        $data = [];
        foreach ($dates['dateArr'] as $cDate) {
            $incomeTotal = 0;
            foreach ($incomes as $row) {
                $chartDates = $cDate;
                $incomeDates = trim(substr($row['date'], 0, 10));
                if ($chartDates == $incomeDates) {
                    $incomeTotal += $row['amount'];
                }
            }
            $incomeTotalArray[] = $incomeTotal;
            $dateArray[] = $cDate;
        }

        //Expense report
        foreach ($dates['dateArr'] as $cDate) {
            $expenseTotal = 0;
            foreach ($expenses as $row) {
                $chartDates = $cDate;
                $expenseDates = trim(substr($row['date'], 0, 10));

                if ($chartDates == $expenseDates) {
                    $expenseTotal += $row['amount'];
                }
            }
            $expenseTotalArray[] = $expenseTotal;
        }

        $data['incomeTotal'] = $incomeTotalArray;
        $data['expenseTotal'] = $expenseTotalArray;
        $data['date'] = $dateArray;

        return $data;
    }

    public function getDate($startDate, $endDate)
    {
        $dateArr = [];
        $subStartDate = '';
        $subEndDate = '';

        if (!($startDate && $endDate)) {
            $data = [
                'dateArr' => $dateArr,
                'startDate' => $subStartDate,
                'endDate' => $subEndDate,
            ];

            return $data;
        }

        $end = trim(substr($endDate, 0, 10));
        $start = Carbon::parse($startDate)->toDateString();
        $startDate = Carbon::createFromFormat('Y-m-d', $start);
        $endDate = Carbon::createFromFormat('Y-m-d', $end);

        while ($startDate <= $endDate) {
            $dateArr[] = $startDate->copy()->format('Y-m-d');
            $startDate->addDay();
        }

        $start = current($dateArr);
        $endDate = end($dateArr);
        $subStartDate = Carbon::parse($start)->startOfDay()->format('Y-m-d H:i:s');
        $subEndDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $data = [
            'dateArr' => $dateArr,
            'startDate' => $subStartDate,
            'endDate' => $subEndDate,
        ];

        return $data;
    }

    public function incomeChartData()
    {
        $periods = CarbonPeriod::create(Carbon::now()->startOfYear(), '1 month', Carbon::now()->endOfYear());

        $dateArr = [];
        foreach ($periods as $date) {
            $monthArr = $date->format('M');
            $dateArr[] = __('messages.dashboard_months.' . $monthArr);
            $income[] = $this->totalIncomeFilterReport($date);
            $expense[] = $this->totalExpenseFilterReport($date);
        }

        $data['days'] = $dateArr;

        $data['income'] = [
            'label' => trans('messages.income', [], getLoggedInUser()->language),
            'data' => $income,
        ];

        $data['expense'] = [
            'label' => trans('messages.income', [], getLoggedInUser()->language),
            'data' => $expense,
        ];

        return $data;
    }

    public function totalIncomeFilterReport($date)
    {
        $month = \Carbon\Carbon::parse($date)->translatedFormat('Y-m');

        return Income::where('date', 'LIKE', "%{$month}%")->sum('amount');
    }

    public function totalExpenseFilterReport($date)
    {
        $month = \Carbon\Carbon::parse($date)->translatedFormat('Y-m');

        return Expense::where('date', 'LIKE', "%{$month}%")->sum('amount');
    }
}
