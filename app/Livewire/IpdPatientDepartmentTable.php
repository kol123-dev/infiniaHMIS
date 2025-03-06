<?php

namespace App\Livewire;

use App\Models\IpdPatientDepartment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class IpdPatientDepartmentTable extends LivewireTableComponent
{
    public $showButtonOnHeader = false;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    protected $model = IpdPatientDepartment::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('ipd_patient_departments.created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.ipd_patient.ipd_number'), 'ipd_number')
                ->view('ipd_patient_list.templates.column.ipd_number')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor.doctorUser.first_name')
                ->view('ipd_patient_list.templates.column.doctor')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.admission_date'), 'admission_date')
                ->view('ipd_patient_list.templates.column.admission_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.bed_id'), 'bed.name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient.discharge'), 'discharge')
                    ->view('ipd_patient_list.templates.column.discharge_date')
                    ->sortable()
                    ->searchable(),
            Column::make(__('messages.ipd_patient.bill_status'), 'bill_status')
                    ->view('ipd_patient_list.templates.column.bill_status'),
            Column::make(__('messages.ipd_patient.net_amount'), 'bill.total_payments')
                    ->view('ipd_patient_list.templates.column.total_charges')
                    ->sortable()
                    ->searchable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor.doctorUser.last_name')
                ->hideIf('doctor_id'),
            Column::make('last_name','patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','patient.patientUser.email')->searchable()->hideIf(1),
            Column::make('last_name','doctor.doctorUser.last_name')->searchable()->hideIf(1),
            Column::make('email','doctor.doctorUser.email')->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        /** @var IpdPatientDepartment $query */
        $query = IpdPatientDepartment::with(['patient.patientUser', 'doctor.doctorUser', 'bed'])
            ->where('patient_id', getLoggedInUser()->owner_id)->select('ipd_patient_departments.*');

        return $query;
    }
}
