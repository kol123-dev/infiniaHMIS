<?php

namespace App\Http\Controllers;

use App\Exports\BloodDonorExport;
use App\Http\Requests\CreateBloodDonorRequest;
use App\Http\Requests\UpdateBloodDonorRequest;
use App\Models\BloodDonation;
use App\Models\BloodDonor;
use App\Repositories\BloodDonorRepository;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class BloodDonorController extends AppBaseController
{
    /** @var BloodDonorRepository */
    private $bloodDonorRepository;

    public function __construct(BloodDonorRepository $bloodDonorRepo)
    {
        $this->bloodDonorRepository = $bloodDonorRepo;
    }

    public function index()
    {
        $bloodGroup = getBloodGroups();

        return view('blood_donors.index', compact('bloodGroup'));
    }

    public function store(CreateBloodDonorRequest $request)
    {
        $input = $request->all();
        $this->bloodDonorRepository->create($input);

        return $this->sendSuccess(__('messages.blood_donor.blood_donor') . ' ' . __('messages.common.saved_successfully'));
    }

    public function edit(BloodDonor $bloodDonor)
    {
        return $this->sendResponse($bloodDonor, 'Blood Donor retrieved successfully.');
    }

    public function update(BloodDonor $bloodDonor, UpdateBloodDonorRequest $request)
    {
        $input = $request->all();
        $this->bloodDonorRepository->update($input, $bloodDonor->id);

        return $this->sendSuccess(__('messages.blood_donor.blood_donor') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(BloodDonor $bloodDonor)
    {
        $bloodDonorModel = [BloodDonation::class];
        $result = canDelete($bloodDonorModel, 'blood_donor_id', $bloodDonor->id);

        if ($result) {
            return $this->sendError(__('messages.blood_donor.blood_donor') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $bloodDonor->delete($bloodDonor->id);

        return $this->sendSuccess(__('messages.blood_donor.blood_donor') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function bloodDonorExport()
    {
        $bloodDonors = BloodDonor::all();
        if (!$bloodDonors) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('blood-donors.index'));
        }
        return Excel::download(new BloodDonorExport, 'blood-donor-' . time() . '.xlsx');
    }
}
