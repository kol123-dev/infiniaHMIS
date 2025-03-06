<?php

namespace App\Http\Controllers;

use App\Exports\NurseExport;
use App\Http\Requests\CreateNurseRequest;
use App\Http\Requests\UpdateNurseRequest;
use App\Models\EmployeePayroll;
use App\Models\Nurse;
use App\Repositories\NurseRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class NurseController extends AppBaseController
{
    /** @var NurseRepository */
    private $nurseRepository;

    public function __construct(NurseRepository $nurseRepo)
    {
        $this->nurseRepository = $nurseRepo;
    }

    public function index()
    {
        $data['statusArr'] = Nurse::STATUS_ARR;

        return view('nurses.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('nurses.create', compact('bloodGroup'));
    }

    public function store(CreateNurseRequest $request)
    {
        $input = $request->all();
        $input['status'] = ! isset($input['status']) ? 0 : 1;

        $nurse = $this->nurseRepository->store($input);

        Flash::success(__('messages.nurses') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('nurses.index'));
    }

    public function show(Nurse $nurse)
    {
        $payrolls = $nurse->payrolls;

        return view('nurses.show', compact('nurse', 'payrolls'));
    }

    public function edit(Nurse $nurse)
    {
        $user = $nurse->user;
        $bloodGroup = getBloodGroups();

        return view('nurses.edit', compact('user', 'nurse', 'bloodGroup'));
    }

    public function update(Nurse $nurse, UpdateNurseRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $user = $this->nurseRepository->update($nurse, $input);

        Flash::success(__('messages.nurses') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('nurses.index'));
    }

    public function destroy(Nurse $nurse)
    {
        $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $nurse->id, $nurse->user->owner_type);
        if ($empPayRollResult) {
            return $this->sendError(__('messages.nurses') . ' ' . __('messages.common.cant_be_deleted'));
        }
        $nurse->user()->delete();
        $nurse->address()->delete();
        $nurse->delete();

        return $this->sendSuccess(__('messages.nurses') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $nurse = Nurse::find($id);
        $status = ! $nurse->user->status;
        $nurse->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function nurseExport()
    {
        $nurses = Nurse::with('user')->get();
        if (!$nurses) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('nurses.index'));
        }
        return Excel::download(new NurseExport, 'nurses-' . time() . '.xlsx');
    }
}
