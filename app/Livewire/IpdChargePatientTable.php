<?php

namespace App\Livewire;

use App\Models\IpdCharge;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class IpdChargePatientTable extends LivewireTableComponent
{
    public $ipdPatientDepartment;

    public $showButtonOnHeader = false;

    public $showFilterOnHeader = false;

    protected $model = IpdCharge::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('ipd_charges.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('standard_charge') || $column->isField('applied_charge')) {
                return [
                    // 'class' => 'text-end',
                    // 'style' => 'padding-right: 1.75rem !important',
                ];
            }

            return [];
        });
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function mount(int $ipdPatientDepartment): void
    {
        $this->ipdPatientDepartment = $ipdPatientDepartment;
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.ipd_patient_charges.date'), 'date')
                ->view('ipd_charges.columns.date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_charges.charge_type_id'), 'charge_type_id')
                ->view('ipd_charges.columns.charge_type')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_charges.charge_category_id'), 'charge_category_id')
                ->view('ipd_charges.columns.charge_category')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_charges.charge_id'), 'charge_id')
                ->view('ipd_charges.columns.code')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_charges.standard_charge'), 'standard_charge')
                ->view('ipd_charges.columns.standard_charge')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_patient_charges.applied_charge'), 'applied_charge')
                ->view('ipd_charges.columns.applied_charge')
                ->sortable()
                ->searchable(),
        ];
    }

    public function builder(): Builder
    {
        return IpdCharge::with(['chargecategory', 'charge'])->where('ipd_patient_department_id',
            $this->ipdPatientDepartment)
            ->select('ipd_charges.*');
    }
}
