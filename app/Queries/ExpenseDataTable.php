<?php

namespace App\Queries;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ExpenseDataTable
 */
class ExpenseDataTable
{
    public function get(array $input = []): Expense
    {
        /** @var Expense $query */
        $query = Expense::query()->select('expenses.*')->with('media');

        $query->when(! empty($input['expense_head']),
            function (Builder $q) use ($input) {
                $q->where('expense_head', $input['expense_head']);
            });

        return $query;
    }
}
