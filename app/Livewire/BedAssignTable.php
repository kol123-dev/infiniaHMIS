<?php

namespace App\Livewire;

use App\Models\BedAssign;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class BedAssignTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'bed_assigns.add-button';

    public $FilterComponent = ['bed_assigns.filter-button', BedAssign::FILTER_STATUS_ARR];

    public $statusFilter;

    protected $model = BedAssign::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function changeFilter($statusFilter)
    {
        $this->resetPage($this->getComputedPageName());
        $this->statusFilter = $statusFilter;
        $this->setBuilder($this->builder());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('bed_assigns.created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton_af');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.ipd_patient.ipd_number'), 'patient.patientUser.email')
                ->view('bed_assigns.columns.case_id')
                ->hideIf('patient.patientUser.email')
                ->searchable(),
            Column::make(__('messages.ipd_patient.ipd_number'), 'ipdPatient.ipd_number')
                ->view('bed_assigns.columns.case_id')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.case.patient'), 'patient.patientUser.first_name')
                ->view('bed_assigns.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.bed_assign.bed'), 'bed.name')
                ->view('bed_assigns.columns.bed')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.bed_assign.assign_date'), 'assign_date')
                ->view('bed_assigns.columns.assign_date')
                ->sortable(),
            Column::make(__('messages.bed_assign.discharge_date'), 'discharge_date')
                ->view('bed_assigns.columns.discharge_date')
                ->sortable(),
            Column::make(__('messages.common.status'), 'status')
                ->view('bed_assigns.columns.status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('bed_assigns.action'),
            Column::make('last_name','patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','patient.patientUser.email')->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        $query = BedAssign::whereHas('patient.patientUser')->with('patient.patientUser', 'bed', 'caseFromBedAssign', 'ipdPatient')
            ->select('bed_assigns.*');
        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 1) {
                $q->where('bed_assigns.status', $this->statusFilter);
            }
            if ($this->statusFilter == 2) {
                $q->where('bed_assigns.status', BedAssign::INACTIVE);
            }
        });

        return $query;
    }
}
