<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountantRequest;
use App\Http\Requests\UpdateAccountantRequest;
use App\Models\Accountant;
use App\Models\EmployeePayroll;
use App\Repositories\AccountantRepository;
use Flash;

class AccountantController extends AppBaseController
{
    /** @var AccountantRepository */
    private $accountantRepository;

    public function __construct(AccountantRepository $accountantRepo)
    {
        $this->accountantRepository = $accountantRepo;
    }

    public function index()
    {
        $data['statusArr'] = Accountant::STATUS_ARR;

        return view('accountants.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('accountants.create', compact('bloodGroup'));
    }

    public function store(CreateAccountantRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $accountant = $this->accountantRepository->store($input);

        Flash::success(__('messages.accountant.accountants').' '.__('messages.common.saved_successfully'));

        return redirect(route('accountants.index'));
    }

    public function show(Accountant $accountant)
    {
        $payrolls = $accountant->payrolls;

        return view('accountants.show', compact('accountant', 'payrolls'));
    }

    public function edit(Accountant $accountant)
    {
        $user = $accountant->user;
        $bloodGroup = getBloodGroups();

        return view('accountants.edit', compact('user', 'accountant', 'bloodGroup'));
    }

    public function update(Accountant $accountant, UpdateAccountantRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $accountant = $this->accountantRepository->update($accountant, $input);

        Flash::success(__('messages.accountant.accountants').' '.__('messages.common.updated_successfully'));

        return redirect(route('accountants.index'));
    }

    public function destroy(Accountant $accountant)
    {
        $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $accountant->id, $accountant->user->owner_type);

        if ($empPayRollResult) {
            return $this->sendError(__('messages.accountant.accountants').' '.__('messages.common.cant_be_deleted'));
        }

        $accountant->user()->delete();
        $accountant->address()->delete();
        $accountant->delete();

        return $this->sendSuccess(__('messages.accountant.accountants').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $accountant = Accountant::find($id);
        $status = ! $accountant->user->status;
        $accountant->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
}
