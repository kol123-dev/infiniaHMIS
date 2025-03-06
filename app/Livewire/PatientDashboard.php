<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\LiveConsultation;
use App\Models\Module;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class PatientDashboard extends Component
{
    public $patientId;
    public $modules;
    public $totlaAppointment;
    public $todayAppointment;
    public $totalMeeting;
    public $patientBill;

    public function mount()
    {
        $this->patientId = getLoggedInUser()->owner_id;
        $this->modules = Module::pluck('is_active', 'name')->toArray();
        $this->totlaAppointment = Appointment::where('patient_id',$this->patientId)->count();
        $this->todayAppointment = Appointment::where('patient_id',$this->patientId)->whereBetween('opd_date',[Carbon::today()->startOfDay(),Carbon::today()->endOfDay()])->count();
        $this->totalMeeting = LiveConsultation::where('patient_id',$this->patientId)->count();
        $this->patientBill = Bill::wherePatientId($this->patientId)->where('status',1)->sum('amount');
    }

    public function render()
    {
        return view('livewire.patient-dashboard');
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.patient_dashboard');
    }
}
