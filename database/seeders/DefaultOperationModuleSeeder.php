<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class DefaultOperationModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            'name' => 'Operation',
            'is_active' => 1,
            'route' => 'operations.index',
        ];

        $module = Module::whereName($input['name'])->first();
        if ($module) {
            $module->update(['route' => $input['route']]);
        } else {
            Module::create($input);
        }
    }
}
