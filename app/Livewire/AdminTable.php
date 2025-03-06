<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Rappasoft\LaravelLivewireTables\Views\Column;

#[Lazy]
class AdminTable extends LivewireTableComponent
{
    protected $model = User::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'admins.add-button';

    public $showFilterOnHeader = true;

    public $FilterComponent = ['admins.filter-button', User::FILTER_STATUS_ARR];

    public $statusFilter;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('users.created_at', 'desc')
            ->setQueryStringStatus(false);
    }

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

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.user.name'), 'first_name')
                ->view('admins.columns.admin')
                ->searchable(function (Builder $query, $direction) {
                    $query->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                })
                ->sortable(),
            Column::make('email','email')->searchable()->hideIf(1),
            Column::make(__('messages.user.phone'), 'phone')
                ->view('admins.columns.phone')
                ->sortable()->searchable(),
            Column::make(__('messages.common.status'), 'status')
                ->view('admins.columns.status')
                ->searchable(),
            Column::make(__('messages.common.action'), 'created_at')
                ->view('admins.action'),
            Column::make(__('last_name'), 'last_name')->hideIf(1),
            Column::make(__('email'), 'email')->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        //        /** @var Admin $query */
        //        $query = Admin::whereHas('user')->with('user')->where('users.id', '!=', getLoggedInUserId())->where('users.department_id', '==', 1)->select('admins.*');

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->with(['roles', 'media'])->where('users.id', '!=', getLoggedInUserId())->select('users.*');

        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 2) {
                $q->where('status', User::ACTIVE);
            }
            if ($this->statusFilter == 1) {
                $q->where('status', User::INACTIVE);
            }
        });

        return $query;
    }
}
