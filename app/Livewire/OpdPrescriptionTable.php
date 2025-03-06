<?php

namespace App\Livewire;

use App\Models\OpdPrescription;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OpdPrescriptionTable extends LivewireTableComponent
{
    protected $model = OpdPrescription::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'opd_prescriptions.add-button';

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public $opdPrescriptionId;

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function mount(string $opdPrescriptionId): void
    {
        $this->opdPrescriptionId = $opdPrescriptionId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.common_skeleton');
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('opd_prescriptions.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('report_type') || $column->isField('opd_patient_department_id') || $column->isField('description')) {
                return [
                    'class' => 'pt-5',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {

        if (! getLoggedinPatient()) {
            $actionButton = Column::make(__('messages.common.action'), 'id')->view('opd_prescriptions.columns.action');
        } else {
            $actionButton = Column::make(__('messages.common.action'), 'id')->view('opd_prescriptions.columns.action');
        }

        return [
            Column::make(__('messages.opd_patient.opd_number'), 'opd_patient_department_id')
                ->view('opd_prescriptions.columns.opd_no')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.patients'), 'patient.patient.patientUser.first_name')
                ->view('opd_prescriptions.columns.patient_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.doctors'),'patient.doctor.doctorUser.first_name')
                ->view('opd_prescriptions.columns.doctor_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.created_on'), 'created_at')
                ->view('opd_prescriptions.columns.created_at')
                ->sortable()
                ->searchable(),
            $actionButton,
            Column::make('last_name','patient.patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','patient.patient.patientUser.email')->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        return OpdPrescription::with('patient','opdPrescriptionItems')->where('opd_patient_department_id', $this->opdPrescriptionId)
        ->select('opd_prescriptions.*');
    }

}
