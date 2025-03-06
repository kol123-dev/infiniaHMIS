<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChargeCategoryRequest;
use App\Http\Requests\UpdateChargeCategoryRequest;
use App\Models\ChargeCategory;
use App\Models\RadiologyTest;
use App\Repositories\ChargeCategoryRepository;

class ChargeCategoryController extends AppBaseController
{
    /** @var ChargeCategoryRepository */
    private $chargeCategoryRepository;

    public function __construct(ChargeCategoryRepository $chargeCategoryRepo)
    {
        $this->chargeCategoryRepository = $chargeCategoryRepo;
    }

    public function index()
    {
        $chargeTypes = ChargeCategory::CHARGE_TYPES;
        asort($chargeTypes);

        return view('charge_categories.index', compact('chargeTypes'));
    }

    public function store(CreateChargeCategoryRequest $request)
    {
        $input = $request->all();

        $this->chargeCategoryRepository->create($input);

        return $this->sendSuccess(__('messages.charge.charge_category').' '.__('messages.common.saved_successfully'));
    }

    public function show(ChargeCategory $chargeCategory)
    {
        $chargeTypes = ChargeCategory::CHARGE_TYPES;

        return view('charge_categories.show', compact('chargeCategory', 'chargeTypes'));
    }

    public function edit(ChargeCategory $chargeCategory)
    {
        return $this->sendResponse($chargeCategory, 'Charge Category Retrieved Successfully.');
    }

    public function update(ChargeCategory $chargeCategory, UpdateChargeCategoryRequest $request)
    {
        $chargeCategory = $this->chargeCategoryRepository->update($request->all(), $chargeCategory->id);

        return $this->sendSuccess(__('messages.charge.charge_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(ChargeCategory $chargeCategory)
    {
        $chargeCategoryModels = [
            RadiologyTest::class,
        ];

        $result = canDelete($chargeCategoryModels, 'charge_category_id', $chargeCategory->id);

        if ($result) {
            return $this->sendError(__('messages.charge.charge_category').' '.__('messages.common.cant_be_deleted'));
        }

        $this->chargeCategoryRepository->delete($chargeCategory->id);

        return $this->sendSuccess(__('messages.charge.charge_category').' '.__('messages.common.deleted_successfully'));
    }
}
