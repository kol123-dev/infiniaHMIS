<?php

namespace App\Http\Controllers;

use App\Exports\BloodDonationExport;
use App\Http\Requests\BloodDonationRequest;
use App\Models\BloodDonation;
use App\Models\BloodDonor;
use App\Repositories\BloodDonationRepository;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class BloodDonationController extends AppBaseController
{
    /** @var BloodDonationRepository */
    private $bloodDonationRepository;

    public function __construct(BloodDonationRepository $bloodDonationRepository)
    {
        $this->bloodDonationRepository = $bloodDonationRepository;
    }

    public function index()
    {
        $donorName = BloodDonor::orderBy('name')->pluck('name', 'id')->toArray();

        return view('blood_donations.index', compact('donorName'));
    }

    public function store(BloodDonationRequest $request)
    {
        try {
            $input = $request->all();
            $this->bloodDonationRepository->createBloodDonation($input);

            return $this->sendSuccess(__('messages.blood_donations') . ' ' . __('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(BloodDonation $bloodDonation)
    {
        return $this->sendResponse($bloodDonation, 'Blood Donation retrieved successfully.');
    }

    public function update(BloodDonationRequest $request, BloodDonation $bloodDonation)
    {
        try {
            $input = $request->all();
            $this->bloodDonationRepository->updateBloodDonation($input, $bloodDonation);

            return $this->sendSuccess(__('messages.blood_donations') . ' ' . __('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy(BloodDonation $bloodDonation)
    {
        try {
            $bloodDonation->delete($bloodDonation->id);

            return $this->sendSuccess(__('messages.iblood_donations') . ' ' . __('messages.common.deleted_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function bloodDonationExport()
    {
        $bloodDonation = BloodDonation::all();
        if (!$bloodDonation) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('blood-donations.index'));
        }
        return Excel::download(new BloodDonationExport, 'blood-donations-' . time() . '.xlsx');
    }
}
