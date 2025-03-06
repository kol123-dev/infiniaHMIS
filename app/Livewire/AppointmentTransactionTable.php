<?php

namespace App\Livewire;

use App\Models\AppointmentTransaction;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class AppointmentTransactionTable extends LivewireTableComponent
{
    protected $model = AppointmentTransaction::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];


    public function configure(): void
    {
        $this->setDefaultSort('appointment_transactions.created_at', 'desc');
        $this->setQueryStringStatus(false);
        $this->setPrimaryKey('id');
        $this->setQueryStringStatus(false);
    }

    public function columns(): array
    {
        return[
            Column::make(__('messages.case.patient'),'appointment.patient.patientUser.first_name')
                ->view('appointment_transaction.columns.patient_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.case.doctor'),'appointment.doctor.doctorUser.first_name')
                ->view('appointment_transaction.columns.doctor_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.opd_patient.appointment_date'),'appointment.opd_date')
                ->view('appointment_transaction.columns.appointment_date')
                ->sortable(),
            Column::make(__('messages.medicine_bills.payment_status'),'appointment.payment_status')
                ->view('appointment_transaction.columns.payment_status')
                ->sortable(),
            Column::make(__('messages.purchase_medicine.payment_mode'),'appointment.payment_type')
                ->view('appointment_transaction.columns.payment_type')
                ->sortable(),
            Column::make(__('messages.ambulance_call.amount'),'amount')
                ->view('appointment_transaction.columns.amount')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.created_at'),'created_at')
                ->view('appointment_transaction.columns.create_at')
                ->sortable()
                ->searchable(),
            Column::make('last_name','appointment.patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','appointment.patient.patientUser.email')->searchable()->hideIf(1),
            Column::make('last_name','appointment.doctor.doctorUser.last_name')->searchable()->hideIf(1),
            Column::make('email','appointment.doctor.doctorUser.email')->searchable()->hideIf(1),
        ];
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.without_add_button_skeleton');
    }

    public function builder(): Builder
    {
        $query = AppointmentTransaction::with('appointment')->select('appointment_transactions.*');

        if(! getLoggedinDoctor()) {
            if(getLoggedinPatient()){
                $patientId = auth()->user()->patient->id;
                $query->whereHas('appointment', function ($q) use ($patientId) {
                    $q->where('patient_id', $patientId);
                });
            }
        }else{
            $doctorId = getLoggedInUser()->owner_id;
            $query->whereHas('appointment', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            });

        }

        return $query;
    }


}
