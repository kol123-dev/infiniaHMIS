<?php

namespace App\Http\Controllers;

use App\Exports\ChargeExport;
use App\Http\Requests\CreateChargeRequest;
use App\Http\Requests\UpdateChargeRequest;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Repositories\ChargeRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class ChargeController extends AppBaseController
{
    /** @var ChargeRepository */
    private $chargeRepository;

    public function __construct(ChargeRepository $chargeRepo)
    {
        $this->chargeRepository = $chargeRepo;
    }

    public function index()
    {
        $chargeTypes = ChargeCategory::CHARGE_TYPES;
        asort($chargeTypes);
        $filterChargeTypes = ChargeCategory::FILTER_CHARGE_TYPES;
        asort($filterChargeTypes);

        return view('charges.index', compact('chargeTypes', 'filterChargeTypes'));
    }

    public function store(CreateChargeRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $charge = $this->chargeRepository->create($input);

        return $this->sendSuccess(__('messages.charges') . ' ' . __('messages.common.saved_successfully'));
    }

    public function show(Charge $charge)
    {
        $chargeTypes = ChargeCategory::CHARGE_TYPES;
        asort($chargeTypes);

        return view('charges.show', compact('charge', 'chargeTypes'));
    }

    public function edit(Charge $charge)
    {
        return $this->sendResponse($charge, 'Charge Retrieved Successfully.');
    }

    public function update(Charge $charge, UpdateChargeRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $charge = $this->chargeRepository->update($input, $charge->id);

        return $this->sendSuccess(__('messages.charges') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(Charge $charge)
    {
        $this->chargeRepository->delete($charge->id);

        return $this->sendSuccess(__('messages.charges') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getChargeCategory(Request $request)
    {
        $id = $request->get('id');

        $chargeCategory = ChargeCategory::where('charge_type', '=', $id)->pluck('name', 'id');

        return $this->sendResponse($chargeCategory, 'Charge Category Retrieved successfully');
    }

    public function chargeExport()
    {
        $charges = Charge::with('chargeCategory')->get();
        if (!$charges) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('charges.index'));
        }
        return Excel::download(new ChargeExport, 'charges-' . time() . '.xlsx');
    }
}
