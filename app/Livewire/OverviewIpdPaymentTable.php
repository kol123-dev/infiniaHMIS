<?php

namespace App\Livewire;

use App\Models\IpdPayment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OverviewIpdPaymentTable extends LivewireTableComponent
{
    public $ipdPaymentId;

    protected $model = IpdPayment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchVisibilityDisabled()
            ->setDefaultSort('created_at', 'desc')
            ->setPerPageVisibilityDisabled();
    }

    public function mount(int $ipdPaymentId)
    {
        $this->ipdPaymentId = $ipdPaymentId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.investigation_report.date'), 'date')
                ->view('ipd_payments.columns.date'),
            Column::make(__('messages.ambulance_call.amount'), 'amount')
                ->view('ipd_payments.columns.amount'),
            Column::make(__('messages.ipd_payments.payment_mode'), 'payment_mode')
                ->view('ipd_payments.columns.payment_mode'),
            Column::make(__('messages.document.document'), 'id')
                ->view('ipd_payments.columns.document'),
            Column::make(__('messages.ambulance.note'), 'notes')
                ->view('ipd_payments.columns.note'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdPayment::where('ipd_patient_department_id',
            $this->ipdPaymentId)->latest()->take(5)->select('ipd_payments.*');

        return $query;
    }
}
