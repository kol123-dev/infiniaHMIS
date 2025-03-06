<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\LunchBreak;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;

#[Lazy]
class LunchBreakTable extends LivewireTableComponent
{
    protected $model = LunchBreak::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'lunch_breaks.add-button';
    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setDefaultSort('created_at', 'desc')
        ->setQueryStringStatus(false);
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.doctor_opd_charge.doctor'), "doctor.doctorUser.first_name")
                ->sortable()
                ->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('doctor.doctorUser', function (Builder $q) use ($direction) {
                            $q->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                        });
                    }
                )
                ->view('lunch_breaks.components.doctor'),
            Column::make(__('messages.visit.doctor'), 'doctor.doctorUser.email')
                ->hideIf('doctor.doctorUser.email')
                ->searchable(),
            Column::make(__('messages.lunch_break.lunch_break').' '.__('messages.common.from'), "break_from")
                ->sortable()
                ->view('lunch_breaks.components.break_from'),
            Column::make(__('messages.lunch_break.lunch_break').' '.__('messages.common.to'), "break_to")
                ->sortable()
                ->view('lunch_breaks.components.break_to'),
            Column::make(__('messages.sms.date'), "date")
                ->sortable()
                ->view('lunch_breaks.components.date'),
            Column::make(__('messages.common.action'), 'id')->view('lunch_breaks.components.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = LunchBreak::with('doctor','doctor.doctorUser')->select('lunch_breaks.*');

        return $query;
    }
}
