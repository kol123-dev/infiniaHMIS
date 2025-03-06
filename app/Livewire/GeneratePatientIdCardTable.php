<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;

#[Lazy]
class GeneratePatientIdCardTable extends LivewireTableComponent
{
    protected $model = Patient::class;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = false;

    public $buttonComponent = 'generate_patient_id_card.add-button';

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setDefaultSort('patients.updated_at', 'desc');
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.patients'), 'patientUser.first_name')->view('generate_patient_id_card.templates.columns.patient')
                ->sortable()->searchable(),
            Column::make(__('messages.patient_id_card.patient_unique_id'), "patient_unique_id")
                ->sortable()
                ->searchable()
                ->view('generate_patient_id_card.templates.columns.patient_unique_id'),
            Column::make(__('messages.patient_id_card.template_id'), "template_id")
                ->sortable()
                ->searchable()
                ->view('generate_patient_id_card.templates.columns.template'),
            Column::make(__('messages.common.action'), "id")
                ->view('generate_patient_id_card.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Patient::whereNot('template_id')->with(['user','idCardTemplate'])->select('*');

        if(Auth::user()->hasRole('Patient')){
            $query->where('user_id',Auth::id())->get();
        }

        return $query;
    }
}
