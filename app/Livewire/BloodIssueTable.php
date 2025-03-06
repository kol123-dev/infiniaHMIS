<?php

namespace App\Livewire;

use App\Models\BloodIssue;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class BloodIssueTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'blood_issues.add-button';

    protected $model = BloodIssue::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //     $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //     $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

    //     $this->setPage($pageNum, $pageName);
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('blood_issues.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('amount')) {
                return [
                    'class' => 'd-flex '.(\getCurrentLoginUserLanguageName() == 'ar' ? 'justify-content-start' : 'justify-content-end'),
                    'style' => (\getCurrentLoginUserLanguageName() == 'ar' ? '' : 'padding-right: 7rem !important'),
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.case.patient'), 'patient.patientUser.first_name')
                ->view('blood_issues.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.case.doctor'), 'doctor.doctorUser.first_name')
                ->view('blood_issues.columns.doctor')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.blood_issue.donor_name'), 'blooddonor.name')
                ->view('blood_issues.columns.donor_name')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.blood_issue.issue_date'), 'issue_date')
                ->view('blood_issues.columns.issue_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.user.blood_group'), 'blooddonor.blood_group')
                ->view('blood_issues.columns.blood_group')
                ->sortable(),
            Column::make(__('messages.blood_issue.amount'), 'amount')
                ->view('blood_issues.columns.amount')
                ->sortable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('blood_issues.action'),
            Column::make('last_name','patient.patientUser.last_name')->searchable()->hideIf(1),
            Column::make('email','patient.patientUser.email')->searchable()->hideIf(1),
            Column::make('last_name','doctor.doctorUser.last_name')->searchable()->hideIf(1),
            Column::make('email','doctor.doctorUser.email')->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        return BloodIssue::whereHas('patient.patientUser')->whereHas('doctor.doctorUser')->with([
            'patient.patientUser', 'doctor.doctorUser', 'blooddonor',
        ])->select('blood_issues.*');
    }
}
