<?php

namespace App\Http\Controllers;

use App\Exports\ReceptionistExport;
use App\Http\Requests\CreateReceptionistRequest;
use App\Http\Requests\UpdateReceptionistRequest;
use App\Models\EmployeePayroll;
use App\Models\Receptionist;
use App\Repositories\ReceptionistRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class ReceptionistController extends AppBaseController
{
    /** @var ReceptionistRepository */
    private $receptionistRepository;

    public function __construct(ReceptionistRepository $receptionistRepo)
    {
        $this->receptionistRepository = $receptionistRepo;
    }

    public function index()
    {
        $data['statusArr'] = Receptionist::STATUS_ARR;

        return view('receptionists.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('receptionists.create', compact('bloodGroup'));
    }

    public function store(CreateReceptionistRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $receptionist = $this->receptionistRepository->store($input);

        Flash::success(__('messages.receptionist.receptionist') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('receptionists.index'));
    }

    public function show(Receptionist $receptionist)
    {
        $payrolls = $receptionist->payrolls;

        return view('receptionists.show', compact('receptionist', 'payrolls'));
    }

    public function edit(Receptionist $receptionist)
    {
        $user = $receptionist->user;
        $bloodGroup = getBloodGroups();

        return view('receptionists.edit', compact('receptionist', 'user', 'bloodGroup'));
    }

    public function update(Receptionist $receptionist, UpdateReceptionistRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $receptionist = $this->receptionistRepository->update($receptionist, $input);

        Flash::success(__('messages.receptionist.receptionist') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('receptionists.index'));
    }

    public function destroy(Receptionist $receptionist)
    {
        $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $receptionist->id, $receptionist->user->owner_type);

        if ($empPayRollResult) {
            return $this->sendError(__('messages.receptionist.receptionist') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $receptionist->user()->delete();
        $receptionist->address()->delete();
        $receptionist->delete();

        return $this->sendSuccess(__('messages.receptionist.receptionist') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $receptionist = Receptionist::find($id);
        $status = ! $receptionist->user->status;
        $receptionist->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function receptionistExport()
    {
        $receptionists = Receptionist::with('user')->get();
        if (!$receptionists) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('receptionists.index'));
        }
        return Excel::download(new ReceptionistExport, 'receptionists-' . time() . '.xlsx');
    }
}
