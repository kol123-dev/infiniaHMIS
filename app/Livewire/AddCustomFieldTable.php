<?php

namespace App\Livewire;

use App\Models\AddCustomFields;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;

#[Lazy]
class AddCustomFieldTable extends LivewireTableComponent
{
    protected $model = AddCustomFields::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'add_custom_fields.add-button';

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('add_custom_fields.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'd-flex justify-content-center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.custom_field.module_name'), 'module_name')
                ->view('add_custom_fields.column.module_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.custom_field.field_type'), 'field_type')
                ->view('add_custom_fields.column.field_type')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.custom_field.field_name'), 'field_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.custom_field.value'), 'values')
                ->view('add_custom_fields.column.field_values')
                ->sortable()
                ->searchable(),

            Column::make(__('messages.common.action'), 'id')
                ->view('add_custom_fields.action'),
        ];
    }
}
