<?php

namespace App\Livewire;

use App\Models\DoctorHoliday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class DoctorHolidayTable extends LivewireTableComponent
{
    protected $model = DoctorHoliday::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'doctor_holiday.components.add_button';

    public $showFilterOnHeader = true;

    public $FilterComponent = ['doctor_holiday.components.filter', []];

    protected $listeners = ['refresh' => '$refresh', 'resetPage', 'changeDateFilter'];

    public $dateFilter = '';

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setQueryStringStatus(false);
    }

    /**
     * @var string[]
     */
    public function changeDateFilter($dateFilter)
    {
        $this->dateFilter = $dateFilter;
        $this->setBuilder($this->builder());
    }

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.doctor_opd_charge.doctor'), 'doctor.doctorUser.first_name')
                ->view('doctor_holiday.components.doctor')
                ->sortable()
                ->searchable(
                    function (Builder $query, $direction) {
                        return $query->whereHas('doctor.doctorUser', function (Builder $q) use ($direction) {
                            $q->whereRaw("TRIM(CONCAT(first_name,' ',last_name,' ')) like '%{$direction}%'");
                        });
                    }
                ),
            Column::make(__('messages.visit.doctor'), 'doctor.doctorUser.email')
                ->hideIf('doctor.doctorUser.email')
                ->searchable(),
            Column::make(__('messages.holiday.reason'), 'name')->view('doctor_holiday.components.reason')
                ->sortable(),
            Column::make(__('messages.sms.date'), 'date')->view('doctor_holiday.components.holiday_date')
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')->view('doctor_holiday.components.action'),
            Column::make('last_name','doctor.doctorUser.last_name')->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        $query = DoctorHoliday::with('doctor','doctor.doctorUser')->select('doctor_holidays.*');

        if ($this->dateFilter != '' && $this->dateFilter != getWeekDate()) {
            $timeEntryDate = explode(' - ', $this->dateFilter);
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $timeEntryDate = explode(' - ', getWeekDate());
            $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
            $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query;
    }
}
