<?php

namespace App\Livewire;

use App\Models\Operation;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class OperationTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'operations.add-button';

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    protected $model = Operation::class;

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.user.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.operation_category.operation_category'), 'operation_category.name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'created_at')
                ->view('operations.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Operation::with('operation_category')->select('operations.*');

        return $query;
    }
}
