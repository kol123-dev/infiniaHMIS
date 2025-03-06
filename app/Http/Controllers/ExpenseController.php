<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use Str;

class ExpenseController extends AppBaseController
{
    /**
     * @var ExpenseRepository
     */
    private $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function index()
    {
        $expenseHeads = Expense::EXPENSE_HEAD;
        asort($expenseHeads);
        $filterExpenseHeads = Expense::FILTER_EXPENSE_HEAD;
        asort($filterExpenseHeads);

        return view('expenses.index', compact('expenseHeads', 'filterExpenseHeads'));
    }

    public function store(CreateExpenseRequest $request)
    {
        $input = $request->all();
        $input['amount'] = removeCommaFromNumbers($input['amount']);
        $this->expenseRepository->store($input);
        $this->expenseRepository->createNotification($input);

        return $this->sendSuccess(__('messages.expenses') . ' ' . __('messages.common.saved_successfully'));
    }

    public function show(Expense $expense)
    {
        $expenses = $this->expenseRepository->find($expense->id);
        $expenseHeads = Expense::EXPENSE_HEAD;
        asort($expenseHeads);

        return view('expenses.show', compact('expenses', 'expenseHeads'));
    }

    public function edit(Expense $expense)
    {
        return $this->sendResponse($expense, 'Expense retrieved successfully.');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->expenseRepository->updateExpense($request->all(), $expense->id);

        return $this->sendSuccess(__('messages.expenses') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(Expense $expense)
    {
        $this->expenseRepository->deleteDocument($expense->id);

        return $this->sendSuccess(__('messages.expenses') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function downloadMedia(Expense $expense)
    {
        $documentMedia = $expense->media[0];
        $documentPath = $documentMedia->getPath();

        if (config('app.media_disc') === 'public') {
            $documentPath = (Str::after($documentMedia->getUrl(), '/uploads'));
        }

        $file = Storage::disk(config('app.media_disc'))->get($documentPath);

        $headers = [
            'Content-Type' => $expense->media[0]->mime_type,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$expense->media[0]->file_name}",
            'filename' => $expense->media[0]->file_name,
        ];

        return response($file, 200, $headers);
    }

    public function expenseExport()
    {
        $expenses = Expense::all();
        if (!$expenses) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('expenses.index'));
        }
        return Excel::download(new ExpenseExport, 'expenses-' . time() . '.xlsx');
    }
}
