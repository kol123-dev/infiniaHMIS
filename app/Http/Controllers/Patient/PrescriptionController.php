<?php

namespace App\Http\Controllers\Patient;

use App\Exports\PrescriptionExport;
use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Exception;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $data['statusArr'] = Prescription::STATUS_ARR;

        return view('patients_prescription_list.index', $data);
    }

    public function show(int $id)
    {
        $prescription = Prescription::find($id);

        if (checkRecordAccess($prescription->patient_id)) {
            return view('errors.404');
        } else {
            return view('patients_prescription_list.show')->with('prescription', $prescription);
        }
    }

    public function prescriptionExport()
    {
        if (getLoggedInUser()->hasRole('Pharmacist')) {
            $prescriptions = Prescription::with(['patient', 'doctor'])->where('status', Prescription::ACTIVE)->get();
            if ($prescriptions->count() != 0) {
                Flash::error(__('messages.common.no_data_available'));
                return redirect()->route('employee.prescriptions');
            }
        } else {
            $prescriptions = Prescription::with(['patient', 'doctor'])->where(
                'patient_id',
                getLoggedInUser()->owner_id
            )->get();
            if ($prescriptions->count() == 0) {
                Flash::error(__('messages.common.no_data_available'));
                return redirect()->route('prescriptions.list');
            }
        }

        return Excel::download(new PrescriptionExport, 'prescriptions-' . time() . '.xlsx');
    }
}
