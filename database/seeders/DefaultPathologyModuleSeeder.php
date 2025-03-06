<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class DefaultPathologyModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            [
                'name' => 'Pathology Units',
                'is_active' => 1,
                'route' => 'pathology.unit.index',
            ],
            [
                'name' => 'Pathology Parameters',
                'is_active' => 1,
                'route' => 'pathology.parameter.index',
            ],
        ];
        foreach ($input as $data) {
            $module = Module::whereName($data['name'])->first();
            if ($module) {
                $module->update(['route' => $data['route']]);
            } else {
                Module::create($data);
            }
        }
    }
}
