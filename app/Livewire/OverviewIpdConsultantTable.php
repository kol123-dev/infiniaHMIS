<?php

namespace App\Livewire;

use App\Models\IpdConsultantRegister;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OverviewIpdConsultantTable extends LivewireTableComponent
{
    public $ipdConsultantId;

    protected $model = IpdConsultantRegister::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchVisibilityDisabled()
            ->setDefaultSort('created_at', 'desc')
            ->setPerPageVisibilityDisabled();
    }

    public function mount(int $ipdConsultantId)
    {
        $this->ipdConsultantId = $ipdConsultantId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.investigation_report.doctor'), 'doctor_id')
                ->view('ipd_consultant_registers.columns.doctor'),
            Column::make(__('messages.ipd_patient_consultant_register.applied_date'), 'applied_date')
                ->view('ipd_consultant_registers.columns.applied_date'),
            Column::make(__('messages.ipd_patient_consultant_register.instruction_date'), 'instruction_date')
                ->view('ipd_consultant_registers.columns.instruction_date'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdConsultantRegister::where('ipd_patient_department_id',
            $this->ipdConsultantId)->latest()->take(5)->select('ipd_consultant_registers.*');

        return $query;
    }
}
