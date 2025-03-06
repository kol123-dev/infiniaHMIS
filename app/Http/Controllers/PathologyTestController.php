<?php

namespace App\Http\Controllers;

use App\Exports\PathologyTestExport;
use App\Http\Requests\CreatePathologyTestRequest;
use App\Http\Requests\UpdatePathologyTestRequest;
use App\Models\Charge;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterItem;
use App\Models\PathologyTest;
use App\Repositories\PathologyTestRepository;
use App\Repositories\PatientRepository;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;

class PathologyTestController extends AppBaseController
{
    /** @var PathologyTestRepository */
    private $pathologyTestRepository;

    /** @var PatientRepository*/
    private $patientRepository;

    public function __construct(PathologyTestRepository $pathologyTestRepo, PatientRepository $patientRepository)
    {
        $this->pathologyTestRepository = $pathologyTestRepo;
        $this->patientRepository =  $patientRepository;
    }

    public function index()
    {
        return view('pathology_tests.index');
    }

    public function create()
    {
        $data = $this->pathologyTestRepository->getPathologyAssociatedData();
        $parameterList = $this->pathologyTestRepository->getParameterDataList();
        $patients = $this->patientRepository->getPatients();

        return view('pathology_tests.create', compact('data', 'parameterList', 'patients'));
    }

    public function store(CreatePathologyTestRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['unit'] = ! empty($input['unit']) ? $input['unit'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = ! empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;

        if ($input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if ($input['parameter_id'][$key] == null) {
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }

                if ($input['patient_result'][$key] == null) {
                    Flash::error(__('messages.new_change.patient_result_required'));
                    return redirect()->back();
                }
            }
        }

        $this->pathologyTestRepository->store($input);

        Flash::success(__('messages.pathology_tests') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('pathology.test.index'));
    }

    public function show(PathologyTest $pathologyTest)
    {
        $pathologyParameterItems = PathologyParameterItem::with('pathologyTest', 'pathologyParameter.pathologyUnit')->wherePathologyId($pathologyTest->id)->get();

        return view('pathology_tests.show', compact('pathologyTest', 'pathologyParameterItems'));
    }

    public function edit(PathologyTest $pathologyTest)
    {
        $data = $this->pathologyTestRepository->getPathologyAssociatedData();
        $parameterList = $this->pathologyTestRepository->getParameterDataList();
        $pathologyParameterItems = $this->pathologyTestRepository->getParameterItemData($pathologyTest->id);
        $patients = $this->patientRepository->getPatients();


        return view('pathology_tests.edit', compact('pathologyTest', 'data', 'parameterList', 'pathologyParameterItems', 'patients'));
    }

    public function update(PathologyTest $pathologyTest, UpdatePathologyTestRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = removeCommaFromNumbers($input['standard_charge']);
        $input['unit'] = ! empty($input['unit']) ? $input['unit'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = ! empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;

        if ($input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if ($input['parameter_id'][$key] == null) {
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }
                if ($input['patient_result'][$key] == null) {
                    Flash::error(__('messages.new_change.patient_result_required'));
                    return redirect()->back();
                }
            }
        }

        $this->pathologyTestRepository->update($input, $pathologyTest);
        Flash::success(__('messages.pathology_tests') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('pathology.test.index'));
    }

    public function destroy(PathologyTest $pathologyTest)
    {
        $pathologyTest->parameterItems()->delete();
        $pathologyTest->delete();

        return $this->sendSuccess(__('messages.pathology_tests') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getStandardCharge($id)
    {
        $standardCharges = Charge::where('charge_category_id', $id)->value('standard_charge');

        return $this->sendResponse($standardCharges, 'StandardCharge retrieved successfully.');
    }

    public function pathologyTestExport()
    {
        $pathologyTests = PathologyTest::with(['pathologycategory', 'chargecategory'])->get();
        if (!$pathologyTests) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('pathology.test.index'));
        }
        return Excel::download(new PathologyTestExport, 'pathology-tests-' . time() . '.xlsx');
    }

    public function showModal(PathologyTest $pathologyTest)
    {
        $pathologyTest->load(['pathologycategory', 'chargecategory']);

        $currency = $pathologyTest->currency_symbol ? strtoupper($pathologyTest->currency_symbol) : strtoupper(getCurrentCurrency());
        $pathologyTest = [
            'test_name' => $pathologyTest->test_name,
            'short_name' => $pathologyTest->short_name,
            'test_type' => $pathologyTest->test_type,
            'pathology_category_name' => $pathologyTest->pathologycategory->name,
            'unit' => $pathologyTest->unit,
            'report_days' => $pathologyTest->report_days,
            'standard_charge' => checkValidCurrency($pathologyTest->currency_symbol ?? getCurrentCurrency()) ? moneyFormat($pathologyTest->standard_charge, $currency) : number_format($pathologyTest->standard_charge) . '' . ($pathologyTest->currency_symbol ? getSymbols($pathologyTest->currency_symbol) : getCurrencySymbol()),
            'subcategory' => $pathologyTest->subcategory,
            'method' => $pathologyTest->method,
            'charge_category_name' => $pathologyTest->chargecategory->name,
            'created_at' => $pathologyTest->created_at,
            'updated_at' => $pathologyTest->updated_at,
        ];

        return $this->sendResponse($pathologyTest, 'Pathology Test Retrieved Successfully.');
    }

    public function getPathologyParameter($id)
    {
        $data = [];
        $data['parameter'] = PathologyParameter::with('pathologyUnit')->whereId($id)->first();

        return $this->sendResponse($data, 'retrieved');
    }

    public function convertToPDF($id)
    {
        $data = [];
        $data['logo'] = $this->pathologyTestRepository->getSettingList();
        $data['pathologyTest'] = PathologyTest::with(['pathologycategory', 'chargecategory'])->where('id', $id)->first();
        $data['pathologyParameterItems'] = PathologyParameterItem::with('pathologyTest', 'pathologyParameter.pathologyUnit')->wherePathologyId($id)->get();

        $pdf = PDF::loadView('pathology_tests.pathology_test_pdf', compact('data'));

        return $pdf->stream('Pathology Test');
    }
}
