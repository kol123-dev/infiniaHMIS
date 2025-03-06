<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PathologyParameter;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;

#[Lazy]
class PathologyParameterTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'pathology_parameter.add-button';

    protected $model = PathologyParameter::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('pathology_parameters.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                    'style' => 'padding-right:20px !important',
                ];
            }
            return [];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('name')) {
                return [
                    'class' => 'pt-5',
                ];
            }
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                    'style' => 'padding-right:20px !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()->hideIf('id'),
            Column::make(__('messages.pathology_category.name'), 'parameter_name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.new_change.reference_range'), 'reference_range')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.item.unit'), 'pathologyUnit.name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('pathology_parameter.action'),

        ];
    }

    public function builder(): Builder
    {
        return PathologyParameter::with('pathologyUnit');
    }
}
