<?php

namespace App\Livewire;

use App\Models\BedType;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class BedTypeTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'bed_types.add-button';

    protected $model = BedType::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('bed_types.created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            return [
                'class' => 'w-100',
            ];
        });

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'w-75 ps-125 pe-5 text-center',
                    'style' => (\getCurrentLoginUserLanguageName() == 'ar' ? 'padding-right: 100px !important' : 'padding-left: 100px !important'),
                ];
            }

            return [
                'class' => 'w-75',
            ];
        });
    }

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

    public function columns(): array
    {
        return [
            Column::make(__('messages.bed.bed_type'), 'title')
                ->view('bed_types.show_route')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('bed_types.action'),
        ];
    }
}
