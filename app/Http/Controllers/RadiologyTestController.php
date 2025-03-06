<?php

namespace App\Http\Controllers;

use App\Exports\RadiologyTestExport;
use App\Http\Requests\CreateRadiologyTestRequest;
use App\Http\Requests\UpdateRadiologyTestRequest;
use App\Models\Charge;
use App\Models\RadiologyTest;
use App\Repositories\RadiologyTestRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;

class RadiologyTestController extends AppBaseController
{
    /** @var RadiologyTestRepository */
    private $radiologyTestRepository;

    public function __construct(RadiologyTestRepository $radiologyTestRepo)
    {
        $this->radiologyTestRepository = $radiologyTestRepo;
    }

    public function index()
    {
        return view('radiology_tests.index');
    }

    public function create()
    {
        $data = $this->radiologyTestRepository->getRadiologyAssociatedData();

        return view('radiology_tests.create', compact('data'));
    }

    public function store(CreateRadiologyTestRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $this->radiologyTestRepository->create($input);
        Flash::success(__('messages.radiology_test.radiology_tests') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('radiology.test.index'));
    }

    public function show(RadiologyTest $radiologyTest)
    {
        return view('radiology_tests.show', compact('radiologyTest'));
    }

    public function edit(RadiologyTest $radiologyTest)
    {
        $data = $this->radiologyTestRepository->getRadiologyAssociatedData();
        $data['chargeCodes'] = Charge::where('charge_category_id', $radiologyTest->charge_category_id)->orderBy('code')->pluck('code', 'id');

        return view('radiology_tests.edit', compact('radiologyTest', 'data'));
    }

    public function update(RadiologyTest $radiologyTest, UpdateRadiologyTestRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $this->radiologyTestRepository->update($input, $radiologyTest->id);
        Flash::success(__('messages.radiology_test.radiology_tests') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('radiology.test.index'));
    }

    public function destroy(RadiologyTest $radiologyTest)
    {
        $radiologyTest->delete();

        return $this->sendSuccess(__('messages.radiology_test.radiology_tests') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getStandardCharge($id)
    {
        $standardCharges = Charge::where('id', $id)->value('standard_charge');

        return $this->sendResponse($standardCharges, __('messages.flash.Standard_charge_retrieved'));
    }

    public function getChargeCode($id)
    {
        $chargeCodes = Charge::where('charge_category_id', $id)->pluck('code', 'id')->toArray();

        return $this->sendResponse($chargeCodes, __('messages.flash.Standard_charge_retrieved'));
    }

    public function radiologyTestExport()
    {
        $radiologyTests = RadiologyTest::with(['radiologycategory', 'chargecategory'])->get();
        if (!$radiologyTests) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('radiology.test.index'));
        }
        return Excel::download(new RadiologyTestExport, 'radiology-tests-' . time() . '.xlsx');
    }

    public function showModal(RadiologyTest $radiologyTest)
    {
        $radiologyTest->load(['radiologycategory', 'chargecategory']);

        $currency = $radiologyTest->currency_symbol ? strtoupper($radiologyTest->currency_symbols) : strtoupper(getCurrentCurrency());
        $radiologyTest = [
            'test_name' => $radiologyTest->test_name,
            'short_name' => $radiologyTest->short_name,
            'test_type' => $radiologyTest->test_type,
            'radiology_category_name' => $radiologyTest->radiologycategory->name,
            'subcategory' => $radiologyTest->subcategory,
            'standard_charge' => checkNumberFormat($radiologyTest->standard_charge, $currency),
            'report_days' => $radiologyTest->report_days,
            'charge_category_name' => $radiologyTest->chargecategory->name,
            'created_at' => $radiologyTest->created_at,
            'updated_at' => $radiologyTest->updated_at,
        ];

        return $this->sendResponse($radiologyTest, 'Radiology Test Retrieved Successfully.');
    }
}
