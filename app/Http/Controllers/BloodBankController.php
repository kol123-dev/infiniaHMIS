<?php

namespace App\Http\Controllers;

use App\Exports\BloodBankExport;
use App\Http\Requests\CreateBloodBankRequest;
use App\Http\Requests\UpdateBloodBankRequest;
use App\Models\BloodBank;
use App\Models\BloodDonor;
use App\Models\User;
use App\Repositories\BloodBankRepository;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class BloodBankController extends AppBaseController
{
    /** @var BloodBankRepository */
    private $bloodBankRepository;

    public function __construct(BloodBankRepository $bloodBankRepo)
    {
        $this->bloodBankRepository = $bloodBankRepo;
    }

    public function index()
    {
        return view('blood_banks.index');
    }

    public function store(CreateBloodBankRequest $request)
    {
        $input = $request->all();
        $this->bloodBankRepository->create($input);

        return $this->sendSuccess(__('messages.hospital_blood_bank.blood_group') . ' ' . __('messages.common.saved_successfully'));
    }

    public function edit(BloodBank $bloodBank)
    {
        return $this->sendResponse($bloodBank, 'BloodBank retrieved successfully.');
    }

    public function update(BloodBank $bloodBank, UpdateBloodBankRequest $request)
    {
        $input = $request->all();
        $this->bloodBankRepository->update($input, $bloodBank->id);

        return $this->sendSuccess(__('messages.hospital_blood_bank.blood_group') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(BloodBank $bloodBank)
    {
        $bloodBankModel = [
            BloodDonor::class,
            User::class,
        ];

        $result = canDelete($bloodBankModel, 'blood_group', $bloodBank->blood_group);

        if ($result) {
            return $this->sendError(__('messages.hospital_blood_bank.blood_group') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $bloodBank->delete($bloodBank->id);

        return $this->sendSuccess(__('messages.hospital_blood_bank.blood_group') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function bloodBankExport()
    {
        $bloodBank = BloodBank::all();
        if (!$bloodBank) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('blood-banks.index'));
        }
        return Excel::download(new BloodBankExport, 'blood-banks-' . time() . '.xlsx');
    }
}
