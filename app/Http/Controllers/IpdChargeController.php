<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdChargeRequest;
use App\Http\Requests\UpdateIpdChargeRequest;
use App\Models\IpdCharge;
use App\Queries\IpdChargesDataTable;
use App\Repositories\IpdChargeRepository;
use DataTables;
use Illuminate\Http\Request;


class IpdChargeController extends AppBaseController
{
    /** @var IpdChargeRepository */
    private $ipdChargeRepository;

    public function __construct(IpdChargeRepository $ipdChargeRepo)
    {
        $this->ipdChargeRepository = $ipdChargeRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdChargesDataTable())->get($request->get('id')))->make(true);
        }
    }

    public function store(CreateIpdChargeRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['applied_charge'] = removeCommaFromNumbers($input['applied_charge']);
        $this->ipdChargeRepository->create($input);
        $this->ipdChargeRepository->createNotification($input);

        return $this->sendSuccess(__('messages.ipd_charges').' '.__('messages.common.saved_successfully'));
    }

    public function edit(IpdCharge $ipdCharge)
    {
        return $this->sendResponse($ipdCharge, 'Ipd Charge retrieved successfully.');
    }

    public function update(IpdCharge $ipdCharge, UpdateIpdChargeRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['applied_charge'] = removeCommaFromNumbers($input['applied_charge']);
        $this->ipdChargeRepository->update($input, $ipdCharge->id);

        return $this->sendSuccess(__('messages.ipd_charges').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdCharge $ipdCharge)
    {
        $ipdCharge->delete();

        return $this->sendSuccess(__('messages.ipd_charges').' '.__('messages.common.deleted_successfully'));
    }

    public function getChargeCategoryList(Request $request)
    {
        $chargeCategories = $this->ipdChargeRepository->getChargeCategories($request->get('id'));

        return $this->sendResponse($chargeCategories, 'Retrieved successfully');
    }

    public function getChargeList(Request $request)
    {
        $charges = $this->ipdChargeRepository->getCharges($request->get('id'));

        return $this->sendResponse($charges, 'Retrieved successfully');
    }

    public function getChargeStandardRate(Request $request)
    {
        $chargeStandardRate = $this->ipdChargeRepository->getChargeStandardRate($request->get('id'),
            $request->get('isEdit'), $request->get('onceOnEditRender'), $request->get('ipdChargeId'));

        return $this->sendResponse($chargeStandardRate, 'Retrieved successfully');
    }
}
