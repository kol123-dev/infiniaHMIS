<?php

namespace App\Livewire;

use App\Models\DoctorOPDCharge;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class DoctorOPDChargeTable extends LivewireTableComponent
{
    public $buttonComponent = 'doctor_opd_charges.templates.button.add-button';

    public $showButtonOnHeader = true;

    protected $model = DoctorOPDCharge::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

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
        $this->setPrimaryKey('id')
            ->setDefaultSort('doctor_opd_charges.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('standard_charge')) {
                return [
                    'class' => 'd-flex'.(getCurrentLoginUserLanguageName() == 'ar' ? 'justify-content-start' : 'justify-content-end'),
                    'style' => 'padding-right: 7rem !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.case.doctor'), 'doctor.doctorUser.first_name')
                ->view('doctor_opd_charges.templates.column.doctor')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.doctor_opd_charge.standard_charge'), 'standard_charge')
                ->view('doctor_opd_charges.templates.column.standard_charge')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('doctor_opd_charges.action'),
            Column::make('last_name','doctor.doctorUser.last_name')->searchable()->hideIf(1),
            Column::make('email','doctor.doctorUser.email')->searchable()->hideIf(1),
        ];
    }

    /**
     * @return Builder|DoctorOPDCharge|\Illuminate\Database\Query\Builder
     */
    public function builder(): Builder
    {
        $query = DoctorOPDCharge::whereHas('doctor')->with('doctor')->select('doctor_opd_charges.*');

        return $query;
    }
}
