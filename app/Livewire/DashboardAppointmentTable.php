<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardAppointmentTable extends LivewireTableComponent
{
    protected $model = Appointment::class;

    public $showButtonOnHeader = false;

    public $showFilterOnHeader = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchStatus(false);
        $this->setPaginationStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.case.patient'), 'patient.patientUser.email')
                ->hideIf('patient.patientUser.email'),
            Column::make(__('messages.case.patient'), 'doctor.doctorUser.email')
                ->hideIf('doctor.doctorUser.email'),
            Column::make(__('messages.case.patient'), 'patient.patientUser.first_name')
                ->view('appointments.templates.columns.patient_name'),
            Column::make(__('messages.case.doctor'), 'doctor.doctorUser.first_name')
                ->view('appointments.templates.columns.doctor_name'),
            Column::make(__('messages.appointment.doctor_department'), 'department.title')
                ->view('appointments.templates.columns.department'),
            Column::make(__('messages.appointment.date'), 'opd_date')
                ->view('appointments.templates.columns.date'),
            Column::make(__('messages.user.status'), 'is_completed')
                ->view('appointments.templates.columns.status')
                ->hideif(getLoggedInUser()->hasRole('Admin'))
        ];
    }

    public function builder(): Builder
    {
        $now = Carbon::now();
        $sixDays = $now->copy()->addDays(6);
        if(getLoggedinPatient()){
            $query = Appointment::with(['patient.user', 'doctor.user'])->whereHas('patient', function ($q) {
                $q->where('user_id', getLoggedinUserId());
            })->whereBetween('opd_date',[$now, $sixDays])->select('appointments.*');

        }else{
            $query = Appointment::with(['patient.user', 'doctor.user'])->whereBetween('opd_date',[$now, $sixDays])->select('appointments.*');

        }

        return $query->latest()->limit(5);
    }
}
