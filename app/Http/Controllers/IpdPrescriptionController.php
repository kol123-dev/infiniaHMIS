<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdPrescriptionRequest;
use App\Http\Requests\UpdateIpdPrescriptionRequest;
use App\Models\IpdPrescription;
use App\Models\Medicine;
use App\Queries\IpdPrescriptionDataTable;
use App\Repositories\IpdPrescriptionRepository;
use DataTables;
use Illuminate\Http\Request;
use PDF;

class IpdPrescriptionController extends AppBaseController
{
    /** @var IpdPrescriptionRepository */
    private $ipdPrescriptionRepository;

    public function __construct(IpdPrescriptionRepository $ipdPrescriptionRepo)
    {
        $this->ipdPrescriptionRepository = $ipdPrescriptionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdPrescriptionDataTable())->get($request->get('id')))->make(true);
        }
    }

    public function store(CreateIpdPrescriptionRequest $request)
    {
        $input = $request->all();
        $arr = collect($input['medicine_id']);
        $duplicateIds = $arr->duplicates();

        foreach ($input['medicine_id'] as $key => $value) {
            $medicine = Medicine::find($input['medicine_id'][$key]);
            $qty = $input['day'][$key] * $input['dose_interval'][$key];

            if (! empty($duplicateIds)) {
                foreach ($duplicateIds as $key => $value) {
                    $medicine = Medicine::find($duplicateIds[$key]);

                    return $this->sendError(__('messages.medicine_bills.duplicate_medicine'));
                }
            }

            if ($medicine->available_quantity < $qty) {
                $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;

                return $this->sendError(__('messages.common.available_quantity_of') .' '.$medicine->name .' '.__('messages.common.is').' '.$available.'.');

            }
        }

        $this->ipdPrescriptionRepository->store($input);
        $this->ipdPrescriptionRepository->createNotification($input);

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.saved_successfully'));
    }

    public function show(IpdPrescription $ipdPrescription)
    {
        return view('ipd_prescriptions.show_ipd_prescription_data', compact('ipdPrescription'))->render();
    }

    public function edit(IpdPrescription $ipdPrescription)
    {
        $ipdPrescription->load(['ipdPrescriptionItems.medicine']);
        $ipdPrescriptionData = $this->ipdPrescriptionRepository->getIpdPrescriptionData($ipdPrescription);

        return $this->sendResponse($ipdPrescriptionData, 'Prescription retrieved successfully.');
    }

    public function update(IpdPrescription $ipdPrescription, UpdateIpdPrescriptionRequest $request)
    {
        $ipdPrescription->load('ipdPrescriptionItems');
        $prescriptionMedicineArray = [];
        $inputdoseAndMedicine = [];

        foreach ($ipdPrescription->ipdPrescriptionItems as $prescriptionMedicine) {
            $prescriptionMedicineArray[$prescriptionMedicine->medicine_id] = $prescriptionMedicine->dosage;
        }

        foreach ($request->medicine_id as $key => $value) {
            $inputdoseAndMedicine[$value] = $request->dosage[$key];
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $arr = collect($input['medicine_id']);
        $duplicateIds = $arr->duplicates();

        foreach ($input['medicine_id'] as $key => $value) {
            $result = array_intersect($prescriptionMedicineArray, $inputdoseAndMedicine);

            $medicine = Medicine::find($input['medicine_id'][$key]);
            $qty = $input['day'][$key] * $input['dose_interval'][$key];

            if (! empty($duplicateIds)) {
                foreach ($duplicateIds as $key => $value) {
                    $medicine = Medicine::find($duplicateIds[$key]);

                    return $this->sendError(__('messages.medicine_bills.duplicate_medicine'));
                }
            }

            if ($medicine->available_quantity < $qty && ! array_key_exists($input['medicine_id'][$key], $result)) {
                $available = $medicine->available_quantity == null ? 0 : $medicine->available_quantity;

                return $this->sendError(__('messages.common.available_quantity_of').' '.$medicine->name.' '.__('messages.common.is').' '.$available.'.');
            }
        }

        $this->ipdPrescriptionRepository->updateIpdPrescriptionItems($request->all(), $ipdPrescription);

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdPrescription $ipdPrescription)
    {
        $ipdPrescription->ipdPrescriptionItems()->delete();
        $ipdPrescription->delete();

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.deleted_successfully'));
    }

    public function getMedicineList(Request $request)
    {
        $chargeCategories = $this->ipdPrescriptionRepository->getMedicines($request->get('id'));

        return $this->sendResponse($chargeCategories, 'Retrieved successfully');
    }

    public function getAvailableMedicineQuantity(Medicine $medicine){

        return $this->sendResponse($medicine, 'Retrieved successfully');
    }

    public function convertToPDF(IpdPrescription $ipdPrescription)
    {
        $pdf = PDF::loadView('ipd_prescriptions.show_ipd_prescription_data_pdf', compact('ipdPrescription'));

        return $pdf->stream('prescription.pdf');
    }
}
