<?php

namespace App\Http\Controllers;

use App\Exports\DoctorOPDChargeExport;
use App\Http\Requests\CreateDoctorOPDChargeRequest;
use App\Http\Requests\UpdateDoctorOPDChargeRequest;
use App\Models\DoctorOPDCharge;
use App\Repositories\DoctorOPDChargeRepository;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class DoctorOPDChargeController extends AppBaseController
{
    /**
     * @var DoctorOPDChargeRepository
     */
    private $doctorOPDChargeRepository;

    public function __construct(DoctorOPDChargeRepository $doctorOPDChargeRepository)
    {
        $this->doctorOPDChargeRepository = $doctorOPDChargeRepository;
    }

    public function index()
    {
        $doctors = $this->doctorOPDChargeRepository->getDoctors();

        return view('doctor_opd_charges.index', compact('doctors'));
    }

    public function store(CreateDoctorOPDChargeRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $this->doctorOPDChargeRepository->create($input);

        return $this->sendSuccess(__('messages.doctor_opd_charges') . ' ' . __('messages.common.saved_successfully'));
    }

    public function edit(DoctorOPDCharge $doctorOPDCharge)
    {
        return $this->sendResponse($doctorOPDCharge, 'Doctor OPD Charge retrieved successfully.');
    }

    public function update(UpdateDoctorOPDChargeRequest $request, DoctorOPDCharge $doctorOPDCharge)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $this->doctorOPDChargeRepository->update($input, $doctorOPDCharge->id);

        return $this->sendSuccess(__('messages.doctor_opd_charges') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(DoctorOPDCharge $doctorOPDCharge)
    {
        $doctorOPDCharge->delete();

        return $this->sendSuccess(__('messages.doctor_opd_charges') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function doctorOPDChargeExport()
    {
        $doctorOPDCharge =  DoctorOPDCharge::with('doctor.user')->get();
        if (!$doctorOPDCharge) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('doctor-opd-charges.index'));
        }
        return Excel::download(new DoctorOPDChargeExport, 'doctor-opd-charges-' . time() . '.xlsx');
    }
}
