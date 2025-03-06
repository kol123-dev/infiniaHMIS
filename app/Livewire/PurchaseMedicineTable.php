<?php

namespace App\Livewire;

use App\Models\PurchaseMedicine;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class PurchaseMedicineTable extends LivewireTableComponent
{
    protected $model = PurchaseMedicine::class;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'purchase-medicines.action';

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

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
        $this->setQueryStringStatus(false);
        $this->setDefaultSort('purchase_medicines.created_at', 'desc');
        $this->setPrimaryKey('id');

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.purchase_medicine.purchase_number'), 'purchase_no')
                ->sortable()->searchable()->view('purchase-medicines.columns.purchase_number'),
            Column::make(__('messages.purchase_medicine.total'), 'total')
                ->sortable()->searchable()->view('purchase-medicines.columns.total'),
            Column::make(__('messages.purchase_medicine.tax'), 'tax')
                ->sortable()->searchable()->view('purchase-medicines.columns.tax'),
            Column::make(__('messages.medicine_bills.payment_status'), 'payment_status')
                ->sortable()->searchable()->view('purchase-medicines.columns.status'),
            Column::make(__('messages.purchase_medicine.net_amount'), 'net_amount')
                ->sortable()->searchable()->view('purchase-medicines.columns.net_amount'),
            Column::make(__('messages.purchase_medicine.payment_mode'), 'payment_type')
                ->sortable()->searchable()->view('purchase-medicines.columns.payment_type'),
            Column::make(__('messages.common.action'), 'id')->view('purchase-medicines.columns.action'),
        ];
    }
}
