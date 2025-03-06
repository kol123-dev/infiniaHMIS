<?php

namespace App\Http\Controllers;

use App\Exports\LabTechnicianExport;
use App\Http\Requests\CreateLabTechnicianRequest;
use App\Http\Requests\UpdateLabTechnicianRequest;
use App\Models\EmployeePayroll;
use App\Models\LabTechnician;
use App\Repositories\LabTechnicianRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class LabTechnicianController extends AppBaseController
{
    /** @var LabTechnicianRepository */
    private $labTechnicianRepository;

    public function __construct(LabTechnicianRepository $labTechnicianRepo)
    {
        $this->labTechnicianRepository = $labTechnicianRepo;
    }

    public function index()
    {
        $data['statusArr'] = LabTechnician::STATUS_ARR;

        return view('lab_technicians.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('lab_technicians.create', compact('bloodGroup'));
    }

    public function store(CreateLabTechnicianRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $labTechnician = $this->labTechnicianRepository->store($input);

        Flash::success(__('messages.lab_technicians') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('lab-technicians.index'));
    }

    public function show(LabTechnician $labTechnician)
    {
        $payrolls = $labTechnician->payrolls;

        return view('lab_technicians.show', compact('labTechnician', 'payrolls'));
    }

    public function edit(LabTechnician $labTechnician)
    {
        $user = $labTechnician->user;
        $bloodGroup = getBloodGroups();

        return view('lab_technicians.edit', compact('labTechnician', 'user', 'bloodGroup'));
    }

    public function update(LabTechnician $labTechnician, UpdateLabTechnicianRequest $request)
    {
        $labTechnician = $this->labTechnicianRepository->update($labTechnician, $request->all());

        Flash::success(__('messages.lab_technicians') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('lab-technicians.index'));
    }

    public function destroy(LabTechnician $labTechnician)
    {
        $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $labTechnician->id, $labTechnician->user->owner_type);
        if ($empPayRollResult) {
            return $this->sendError(__('messages.lab_technicians') . ' ' . __('messages.common.cant_be_deleted'));
        }
        $labTechnician->user()->delete();
        $labTechnician->address()->delete();
        $labTechnician->delete();

        return $this->sendSuccess(__('messages.lab_technicians') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $labTechnician = LabTechnician::find($id);
        $status = ! $labTechnician->user->status;
        $labTechnician->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function labTechnicianExport()
    {
        $labTechnicians = LabTechnician::with('user')->get();
        if (!$labTechnicians) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('lab-technicians.index'));
        }

        return Excel::download(new LabTechnicianExport, 'lab-technicians-' . time() . '.xlsx');
    }
}
