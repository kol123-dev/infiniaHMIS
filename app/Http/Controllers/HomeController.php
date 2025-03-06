<?php

namespace App\Http\Controllers;

use App\Models\Accountant;
use App\Models\AdvancedPayment;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Enquiry;
use App\Models\IpdBill;
use App\Models\IpdPatientDepartment;
use App\Models\LabTechnician;
use App\Models\LiveConsultation;
use App\Models\Module;
use App\Models\NoticeBoard;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Pharmacist;
use App\Models\Receptionist;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends AppBaseController
{
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->middleware('auth');
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $data['invoiceAmount'] = totalAmount();
        $data['billAmount'] = Bill::sum('amount');
        $data['paymentAmount'] = Payment::sum('amount');
        $data['advancePaymentAmount'] = AdvancedPayment::sum('amount');
        $data['doctors'] = Doctor::count();
        $data['patients'] = Patient::count();
        $data['nurses'] = Nurse::count();
        $data['accountants'] = Accountant::count();
        $data['labTechnicians'] = LabTechnician::count();
        $data['pharmacists'] = Pharmacist::count();
        $data['receptionists'] = Receptionist::count();
        $data['availableBeds'] = Bed::whereIsAvailable(1)->count();
        $data['noticeBoards'] = NoticeBoard::take(7)->orderBy('id', 'DESC')->get();
        $data['enquiries'] = Enquiry::where('status', 0)->latest()->take(5)->get();
        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->with(['roles', 'media'])->where('users.id', '!=', getLoggedInUserId())->get()->count();
        $data['admins'] = $admin;
        $data['currency'] = Setting::CURRENCIES;
        $modules = Module::pluck('is_active', 'name')->toArray();

        return view('dashboard.index', compact('data', 'modules'));
    }

    public function patientDashboard() {
        $patientId = getLoggedInUser()->owner_id;
        $modules = Module::pluck('is_active', 'name')->toArray();
        $data['totlaAppointment'] = Appointment::where('patient_id',$patientId)->count();
        $data['todayAppointment'] = Appointment::where('patient_id',$patientId)->whereBetween('opd_date',[Carbon::today()->startOfDay(),Carbon::today()->endOfDay()])->count();
        $data['totalMeeting'] = LiveConsultation::where('patient_id',$patientId)->count();
        $data['patientBill'] = Bill::wherePatientId($patientId)->where('status',1)->sum('amount');
        // $ipdDept = IpdPatientDepartment::whereHas('patient', function($q) {
        //     $q->where('user_id', getLoggedInUserId());
        // })->pluck('id')->toArray();
        // $data['IpdAmount'] = IpdBill::whereIn('ipd_patient_department_id',$ipdDept)->sum('net_payable_amount');
        return view('dashboard.patient-dashboard', compact('modules','data'));
    }

    public function dashboardChart()
    {
        $data = $this->dashboardRepository->incomeChartData();

        return $this->sendResponse($data, 'Income report generated');
    }

    public function incomeExpenseReport(Request $request)
    {
        $data = $this->dashboardRepository->getIncomeExpenseReport($request->all());

        return $this->sendResponse($data, 'Income and Expense report retrieved successfully.');
    }
}
