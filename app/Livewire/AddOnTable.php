<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AddOn;
use Laracasts\Flash\Flash;
use Livewire\Component;

class AddOnTable extends Component
{
    protected $model = AddOn::class;

    public $addOnModules = [];

    public function mount()
    {
        $this->addOnModules = AddOn::orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.add-on-module');
    }
}
