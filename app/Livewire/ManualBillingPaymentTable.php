<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ManualBillPayment;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;

#[Lazy]
class ManualBillingPaymentTable extends LivewireTableComponent
{
    protected $model = ManualBillPayment::class;

    public $showButtonOnHeader = false;

    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setDefaultSort('bill_transactions.created_at', 'desc');
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
        return view('livewire.skeleton_files.without_add_button_skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.case.patient'), "id")
                ->sortable()
                ->searchable()
                ->view('manual_bill_payments.column.patient'),
            Column::make(__('messages.medicine_bills.payment_status'), "payment_type")
                ->sortable()
                ->view('manual_bill_payments.column.payment_type'),
            Column::make(__('messages.user.status'), "status")
                ->sortable()
                ->view('manual_bill_payments.column.status'),
            Column::make(__('messages.bill.transaction_date'), "id")
                ->sortable()
                ->view('manual_bill_payments.column.transaction_date'),
            Column::make(__('messages.invoice.amount'), "id")
                ->sortable()
                ->view('manual_bill_payments.column.amount'),
        ];
    }

    public function builder(): Builder
    {
        $query = ManualBillPayment::whereHas('bill.patient.patientUser')->with(['bill.patient.patientUser.media'])->select('bill_transactions.*');
        $query->where('payment_type',Bill::Manually);

        return $query;
    }
}
