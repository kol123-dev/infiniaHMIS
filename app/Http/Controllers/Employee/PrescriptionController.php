<?php

namespace App\Http\Controllers\Employee;

use App\Exports\PrescriptionExport;
use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Repositories\PrescriptionRepository;
use Exception;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrescriptionController extends Controller
{
    private $prescriptionRepository;

    public function index()
    {
        return view('employee_prescription_list.index');
    }

    public function show($id)
    {
        $prescriptionRepository = App::make(PrescriptionRepository::class);
        $data = $prescriptionRepository->getSettingList();
        $prescription = $prescriptionRepository->getData($id);
        $medicines = $prescriptionRepository->getMedicineData($id);

        return view('prescriptions.view', compact('prescription', 'medicines', 'data'));
    }

    public function prescriptionExport()
    {
        if (getLoggedInUser()->hasRole('Pharmacist')) {
            $prescriptions = Prescription::with(['patient', 'doctor'])->where('status', Prescription::ACTIVE)->get();
            if ($prescriptions->count() == 0) {
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
