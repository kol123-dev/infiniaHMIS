<?php

namespace App\Livewire;

use App\Models\IpdOperation;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OverviewIpdOperationTable extends LivewireTableComponent
{
    public $ipdOperationId;

    protected $model = IpdOperation::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchVisibilityDisabled()
            ->setDefaultSort('created_at', 'desc')
            ->setPerPageVisibilityDisabled();

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('ipd_patient_department_id')) {
                return [
                    'class' => 'd-flex justify-content-end w-75 ps-125 text-center',
                    'style' => 'width: 85% !important',
                ];
            }

            return [
                'class' => 'w-100',
            ];
        });
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function mount(int $ipdOperationId)
    {
        $this->ipdOperationId = $ipdOperationId;
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.prescription.reference_id'), 'id')
                ->view('ipd_operation.columns.ref_no'),
            Column::make(__('messages.operation.operation_date'), 'ref_no')
                ->view('ipd_operation.columns.operation_date'),
            Column::make(__('messages.operation.operation_name'), 'operations.name'),
            Column::make(__('messages.operation.operation_category_name'), 'operations.operation_category.name'),
            Column::make(__('messages.operation.ot_technician'), 'ipd_patient_department_id')
                ->view('ipd_operation.columns.ot_technician'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdOperation::with('operations.operation_category')->where('ipd_patient_department_id',
            $this->ipdOperationId)->latest()->take(5)->get();
        if (count($query) > 0) {
            return $query->toQuery()->select('ipd_operation.*');
        } else {
            return IpdOperation::with('operations.operation_category')->where('ipd_patient_department_id',
                $this->ipdOperationId)->select('ipd_operation.*');
        }
    }
}
