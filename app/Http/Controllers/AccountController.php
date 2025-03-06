<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Payment;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;

class AccountController extends AppBaseController
{
    /** @var AccountRepository */
    private $accountRepository;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepository = $accountRepo;
    }

    public function index(Request $request)
    {
        $data['statusArr'] = Account::STATUS_ARR;
        $data['typeArr'] = Account::TYPE_ARR;

        return view('accounts.index')->with($data);
    }

    public function store(CreateAccountRequest $request)
    {
        $input = $request->all();
        $this->accountRepository->create($input);

        return $this->sendSuccess(__('messages.account.account').' '.__('messages.common.saved_successfully'));
    }

    public function show(Account $account)
    {
        $payments = $account->payments;

        return view('accounts.show', compact('payments', 'account'));
    }

    public function edit(Account $account)
    {
        return $this->sendResponse($account, 'Account retrived successfully.');
    }

    public function update(Account $account, UpdateAccountRequest $request)
    {
        $this->accountRepository->update($request->all(), $account->id);

        return $this->sendSuccess(__('messages.account.account').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Account $account)
    {
        $accountModel = [
            Payment::class,
        ];

        $result = canDelete($accountModel, 'account_id', $account->id);

        if ($result) {
            return $this->sendError(__('messages.account.account').' '.__('messages.common.cant_be_deleted'));
        }

        $this->accountRepository->delete($account->id);

        return $this->sendSuccess(__('messages.account.account').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveAccount(Account $account)
    {
        $account->status = ! $account->status;
        $account->save();

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
}
