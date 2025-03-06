<?php

namespace App\Http\Controllers;

use App\Exports\PharmacistExport;
use App\Http\Requests\CreatePharmacistRequest;
use App\Http\Requests\UpdatePharmacistRequest;
use App\Models\EmployeePayroll;
use App\Models\Pharmacist;
use App\Repositories\PharmacistRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class PharmacistController extends AppBaseController
{
    /** @var PharmacistRepository */
    private $pharmacistRepository;

    public function __construct(PharmacistRepository $pharmacistRepo)
    {
        $this->pharmacistRepository = $pharmacistRepo;
    }

    public function index()
    {
        $data['statusArr'] = Pharmacist::STATUS_ARR;

        return view('pharmacists.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('pharmacists.create', compact('bloodGroup'));
    }

    public function store(CreatePharmacistRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;

        $this->pharmacistRepository->store($input);
        Flash::success(__('messages.pharmacists') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('pharmacists.index'));
    }

    public function show(Pharmacist $pharmacist)
    {
        $payrolls = $pharmacist->payrolls;

        return view('pharmacists.show', compact('pharmacist', 'payrolls'));
    }

    public function edit(Pharmacist $pharmacist)
    {
        $user = $pharmacist->user;
        $bloodGroup = getBloodGroups();

        return view('pharmacists.edit', compact('pharmacist', 'user', 'bloodGroup'));
    }

    public function update(Pharmacist $pharmacist, UpdatePharmacistRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $this->pharmacistRepository->update($input, $pharmacist);

        Flash::success(__('messages.pharmacists') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('pharmacists.index'));
    }

    public function destroy(Pharmacist $pharmacist)
    {
        $empPayRollResult = canDeletePayroll(EmployeePayroll::class, 'owner_id', $pharmacist->id, $pharmacist->user->owner_type);

        if ($empPayRollResult) {
            return $this->sendError(__('messages.pharmacists') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $pharmacist->user()->delete();
        $pharmacist->delete();
        $pharmacist->address()->delete();

        return $this->sendSuccess(__('messages.pharmacists') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $pharmacist = Pharmacist::find($id);
        $status = ! $pharmacist->user->status;
        $pharmacist->user()->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function pharmacistExport()
    {
        $pharmacists = Pharmacist::with('user')->get();
        if (!$pharmacists) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('pharmacists.index'));
        }
        return Excel::download(new PharmacistExport, 'pharmacists-' . time() . '.xlsx');
    }
}
