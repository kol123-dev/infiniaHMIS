<?php

namespace App\Livewire;

use App\Models\IpdOperation;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class IpdOperationTable extends LivewireTableComponent
{
    protected $model = IpdOperation::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'ipd_operation.add-button';

    public $ipdOperationId;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('ipd_operation.created_at', 'desc');
    }

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function mount(int $ipdOperationId)
    {
        $this->ipdOperationId = $ipdOperationId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.common_skeleton');
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.prescription.reference_id'), 'ref_no')
                ->sortable()
                ->searchable()
                ->view('ipd_operation.columns.ref_no'),
            Column::make(__('messages.operation.operation_date'), 'operation_date')
                ->sortable()
                ->searchable()
                ->view('ipd_operation.columns.operation_date'),
            Column::make(__('messages.operation.operation_name'), 'operations.name')
                ->sortable(),
            Column::make(__('messages.operation.operation_category_name'), 'operations.operation_category.name')
                ->sortable(),
            Column::make(__('messages.operation.ot_technician'), 'ot_technician')
                ->sortable()
                ->searchable()
                ->view('ipd_operation.columns.ot_technician'),
            Column::make(__('messages.common.action'), 'id')
                ->view('ipd_operation.columns.action'),
        ];
    }

    public function builder(): Builder
    {
        return IpdOperation::with(['ipd_patient_department', 'operations.operation_category'])->where('ipd_patient_department_id', $this->ipdOperationId)->select('ipd_operation.*');
    }
}
