<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdPrescriptionRequest;
use App\Http\Requests\CreateOpdPrescriptionRequest;
use App\Http\Requests\UpdateOpdPrescriptionRequest;
use App\Models\Medicine;
use App\Models\OpdPrescription;
use App\Queries\OpdPrescriptionDataTable;
use App\Repositories\OpdPresciptionRepository;
use DataTables;
use Illuminate\Http\Request;
use PDF;

class OpdPrescriptionController extends AppBaseController
{
    /**  @var  OpdPresciptionRepository */
    private $opdPrescriptionRepository;

    public function __construct(OpdPresciptionRepository $opdPrescriptionRepo)
    {
        $this->opdPrescriptionRepository = $opdPrescriptionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new OpdPrescriptionDataTable())->get($request->get('id')))->make(true);
        }
    }

    public function store(CreateOpdPrescriptionRequest $request)
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

        $this->opdPrescriptionRepository->store($input);
        $this->opdPrescriptionRepository->createNotification($input);

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.saved_successfully'));
    }

    public function edit(OpdPrescription $opdPrescription)
    {
        $opdPrescription->load(['opdPrescriptionItems.medicine']);
        $opdPrescriptionData = $this->opdPrescriptionRepository->getOpdPrescriptionData($opdPrescription);

        return $this->sendResponse($opdPrescriptionData, 'Prescription retrieved successfully.');
    }

    public function update(OpdPrescription $opdPrescription, UpdateOpdPrescriptionRequest $request)
    {
        $opdPrescription->load('opdPrescriptionItems');
        $prescriptionMedicineArray = [];
        $inputdoseAndMedicine = [];

        foreach ($opdPrescription->opdPrescriptionItems as $prescriptionMedicine) {
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

        $this->opdPrescriptionRepository->updateOpdPrescriptionItems($request->all(), $opdPrescription);

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.updated_successfully'));
    }

    public function show(OpdPrescription $opdPrescription)
    {
        return view('opd_prescriptions.show_opd_prescription_data',compact('opdPrescription'))->render();
    }

    public function destroy(OpdPrescription $opdPrescription)
    {
        $opdPrescription->opdPrescriptionItems()->delete();
        $opdPrescription->delete();

        return $this->sendSuccess(__('messages.ipd_prescription').' '.__('messages.common.deleted_successfully'));
    }

    public function getMedicineList(Request $request)
    {
        $chargeCategories = $this->opdPrescriptionRepository->getMedicines($request->get('id'));

        return $this->sendResponse($chargeCategories, 'Retrieved successfully');
    }

    public function getAvailableMedicineQuantity(Medicine $medicine)
    {
        return $this->sendResponse($medicine, 'Retrieved successfully');
    }

    public function convertToPDF(OpdPrescription $opdPrescription)
    {
        $pdf = PDF::loadView('opd_prescriptions.show_opd_prescription_data_pdf', compact('opdPrescription'));

        return $pdf->stream('prescription.pdf');
    }
}
