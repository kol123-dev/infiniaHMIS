<?php

namespace App\Livewire;

use App\Models\IpdCharge;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OverviewIpdChargesTable extends LivewireTableComponent
{
    public $ipdChargeId;

    protected $model = IpdCharge::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchVisibilityDisabled()
            ->setDefaultSort('created_at', 'desc')
            ->setPerPageVisibilityDisabled();
    }

    public function mount(int $ipdChargeId)
    {
        $this->ipdChargeId = $ipdChargeId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.investigation_report.date'), 'ipd_patient_department_id')
                ->view('ipd_charges.columns.date'),
            Column::make(__('messages.charge_category.charge_type'), 'charge_type_id')
                ->view('ipd_charges.columns.charge_type'),
            Column::make(__('messages.charge.code'), 'charge.code')
                ->view('ipd_charges.columns.code'),
            Column::make(__('messages.charge.standard_charge'), 'standard_charge')
                ->view('ipd_charges.columns.standard_charge'),
            Column::make(__('messages.ipd_patient_charges.applied_charge'), 'applied_charge')
                ->view('ipd_charges.columns.applied_charge'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdCharge::with('charge')->where('ipd_patient_department_id',
            $this->ipdChargeId)->latest()->take(5)->select('ipd_charges.*');

        return $query;
    }
}
