<?php

namespace App\Livewire;

use App\Models\IpdDiagnosis;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
// use Livewire\Attributes\Lazy;

// #[Lazy]
class OverviewIpdDiagnosisTable extends LivewireTableComponent
{
    public $ipdDiagnosisId;

    protected $model = IpdDiagnosis::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchVisibilityDisabled()
            ->setDefaultSort('created_at', 'desc')
            ->setPerPageVisibilityDisabled();
    }

    public function mount(int $ipdDiagnosisId)
    {
        $this->ipdDiagnosisId = $ipdDiagnosisId;
    }

    // public function placeholder()
    // {
    //     return view('livewire.skeleton_files.without_add_button_skeleton');
    // }

    public function columns(): array
    {
        return [
            Column::make(__('messages.ipd_patient_diagnosis.report_type'), 'report_type'),
            Column::make(__('messages.ipd_patient_diagnosis.report_date'), 'report_date')
                ->view('ipd_diagnoses.columns.report_date'),
            Column::make(__('messages.document.document'), 'id')
                ->view('ipd_diagnoses.columns.document'),
            Column::make(__('messages.ipd_patient_diagnosis.description'), 'report_date')
                ->view('ipd_diagnoses.columns.description'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdDiagnosis::where('ipd_patient_department_id',
            $this->ipdDiagnosisId)->latest()->take(5)->select('ipd_diagnoses.*');

        return $query;
    }
}
