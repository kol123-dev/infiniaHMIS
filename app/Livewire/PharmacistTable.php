<?php

namespace App\Livewire;

use App\Models\Pharmacist;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class PharmacistTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $buttonComponent = 'pharmacists.add-button';

    public $FilterComponent = ['pharmacists.filter-button', Pharmacist::FILTER_STATUS_ARR];

    public $statusFilter;

    protected $model = Pharmacist::class;

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

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton_af');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('pharmacists.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '3') {
                return [
                    'width' => '8%',
                ];
            }
            if ($columnIndex == '4') {
                return [
                    'width' => '10%',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.pharmacist.pharmacists'), 'user.first_name')->view('pharmacists.columns.pharmacist')
                ->sortable()
                ->searchable(function (Builder $query, $direction) {
                    $query->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                }),
            Column::make(__('messages.pharmacist.pharmacists'), 'user_id')->hideIf(1),
            Column::make(__('messages.user.blood_group'), 'user.blood_group')->view('pharmacists.columns.blood_group')
                ->sortable(),
            Column::make(__('messages.common.status'), 'user.status')
                ->view('pharmacists.columns.status'),
            Column::make(__('messages.common.action'), 'id')->view('pharmacists.action'),
            Column::make('email','user.email')->searchable()->hideIf(1),

        ];
    }

    public function builder(): Builder
    {
        $query = Pharmacist::whereHas('user')->with('user.media')->select('pharmacists.*');

        $query->when(isset($this->statusFilter), function (Builder $q) {
            $q->whereHas('user', function (Builder $query) {
                if ($this->statusFilter == 1) {
                    $query->where('status', Pharmacist::ACTIVE);
                }
                if ($this->statusFilter == 2) {
                    $query->where('status', Pharmacist::INACTIVE);
                }
            });
        });

        return $query;
    }
}
