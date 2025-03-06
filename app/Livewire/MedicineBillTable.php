<?php

namespace App\Livewire;

use App\Models\MedicineBill;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;

#[Lazy]
class MedicineBillTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'medicine-bills.add-button';

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    protected $model = MedicineBill::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('medicine_bills.created_at', 'desc');

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center ml-5',
                ];
            }

            return [];
        });

    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.medicine_bills.bill_number'), 'bill_number')
                ->sortable()->searchable()->view('medicine-bills.columns.bill_id'),
            Column::make(__('messages.case.date'), 'created_at')
                ->sortable()->view('medicine-bills.columns.bill_date'),
            Column::make(__('messages.invoice.patient'), 'patient.patientUser.first_name')
                ->sortable()
                ->searchable()
                ->view('medicine-bills.columns.patient'),
            Column::make(__('messages.case.doctor'), 'doctor.doctorUser.first_name')
                ->sortable()
                ->searchable()
                ->view('medicine-bills.columns.doctor'),
            Column::make(__('messages.ipd_payments.payment_mode'), 'payment_type')
                ->view('medicine-bills.columns.payment_mode'),
            Column::make(__('messages.purchase_medicine.net_amount'), 'net_amount')
                ->sortable()->view('medicine-bills.columns.amount'),
            Column::make(__('messages.medicine_bills.payment_status'), 'payment_status')
                ->sortable()->view('medicine-bills.columns.payment_status'),
            Column::make(__('messages.common.action'), 'id')
                ->sortable()->view('medicine-bills.columns.action'),
            Column::make('last_name','patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','patient.patientUser.email')->searchable()->hideIf(1),
            Column::make('last_name','doctor.doctorUser.last_name')->searchable()->hideIf(1),
            Column::make('email','doctor.doctorUser.email')->searchable()->hideIf(1),
        ];
    }

    public function Builder(): Builder
    {
        return MedicineBill::with('patient','doctor','saleMedicine')->select('medicine_bills.*');
    }
}
