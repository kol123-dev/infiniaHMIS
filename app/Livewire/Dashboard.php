<?php

namespace App\Livewire;

use App\Models\Accountant;
use App\Models\AdvancedPayment;
use App\Models\Bed;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Enquiry;
use App\Models\LabTechnician;
use App\Models\Module;
use App\Models\NoticeBoard;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Pharmacist;
use App\Models\Receptionist;
use App\Models\Setting;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class Dashboard extends Component
{
    public $invoiceAmount;
    public $billAmount;
    public $paymentAmount;
    public $advancePaymentAmount;
    public $doctors;
    public $patients;
    public $nurses;
    public $accountants;
    public $labTechnicians;
    public $pharmacists;
    public $receptionists;
    public $availableBeds;
    public $noticeBoards;
    public $enquiries;
    public $admins;
    public $currency;
    public $modules;

    public function mount()
    {
        $this->invoiceAmount = totalAmount();
        $this->billAmount = Bill::sum('amount');
        $this->paymentAmount = Payment::sum('amount');
        $this->advancePaymentAmount = AdvancedPayment::sum('amount');
        $this->doctors = Doctor::count();
        $this->patients = Patient::count();
        $this->nurses = Nurse::count();
        $this->accountants = Accountant::count();
        $this->labTechnicians = LabTechnician::count();
        $this->pharmacists = Pharmacist::count();
        $this->receptionists = Receptionist::count();
        $this->availableBeds = Bed::whereIsAvailable(1)->count();
        $this->noticeBoards = NoticeBoard::take(7)->orderBy('id', 'DESC')->get();
        $this->enquiries = Enquiry::where('status', 0)->latest()->take(5)->get();
        $this->admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->with(['roles', 'media'])->where('users.id', '!=', getLoggedInUserId())->get()->count();
        $this->currency = Setting::CURRENCIES;
        $this->modules = Module::pluck('is_active', 'name')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.admin_dashboard_skeleton');
    }
}
