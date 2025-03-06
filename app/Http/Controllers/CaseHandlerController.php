<?php

namespace App\Http\Controllers;

use App\Exports\CaseHandlerExport;
use App\Http\Requests\CreateCaseHandlerRequest;
use App\Http\Requests\UpdateCaseHandlerRequest;
use App\Models\CaseHandler;
use App\Models\EmployeePayroll;
use App\Repositories\CaseHandlerRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class CaseHandlerController extends AppBaseController
{
    /** @var CaseHandlerRepository */
    private $caseHandlerRepository;

    public function __construct(CaseHandlerRepository $caseHandlerRepo)
    {
        $this->caseHandlerRepository = $caseHandlerRepo;
    }

    public function index()
    {
        $data['statusArr'] = CaseHandler::STATUS_ARR;

        return view('case_handlers.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('case_handlers.create', compact('bloodGroup'));
    }

    public function store(CreateCaseHandlerRequest $request)
    {
        $input = $request->all();
        $input['status'] = ! isset($input['status']) ? 0 : 1;
        $this->caseHandlerRepository->store($input);
        Flash::success(__('messages.case_handlers') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('case-handlers.index'));
    }

    public function show(CaseHandler $caseHandler)
    {
        $payrolls = $caseHandler->payrolls;

        return view('case_handlers.show', compact('caseHandler', 'payrolls'));
    }

    public function edit(CaseHandler $caseHandler)
    {
        $user = $caseHandler->user;
        $bloodGroup = getBloodGroups();

        return view('case_handlers.edit', compact('user', 'caseHandler', 'bloodGroup'));
    }

    public function update(CaseHandler $caseHandler, UpdateCaseHandlerRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $this->caseHandlerRepository->update($caseHandler, $input);
        Flash::success(__('messages.case_handlers') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('case-handlers.index'));
    }

    public function destroy(CaseHandler $caseHandler)
    {
        $caseHandlersModels = [
            EmployeePayroll::class,
        ];

        $result = canDelete($caseHandlersModels, 'owner_id', $caseHandler->id);

        if ($result) {
            return $this->sendError(__('messages.case_handlers') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $caseHandler->user()->delete();
        $caseHandler->address()->delete();
        $caseHandler->delete();

        return $this->sendSuccess(__('messages.case_handlers') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $caseHandler = CaseHandler::find($id);
        $status = ! $caseHandler->user->status;
        $caseHandler->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function caseHandlerExport()
    {
        $caseHandler = CaseHandler::with('user')->get();
        if (!$caseHandler) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('case-handlers.index'));
        }
        return Excel::download(new CaseHandlerExport, 'case-handlers-' . time() . '.xlsx');
    }
}
