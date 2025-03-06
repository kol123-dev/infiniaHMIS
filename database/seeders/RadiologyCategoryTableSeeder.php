<?php

namespace Database\Seeders;

use App\Models\RadiologyCategory;
use Illuminate\Database\Seeder;

class RadiologyCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            [
                'name' => 'X-Ray',
            ],
            [
                'name' => 'Sonography',
            ],
            [
                'name' => 'CT Scan',
            ],
            [
                'name' => 'MRI',
            ],
            [
                'name' => 'ECG',
            ],
        ];

        foreach ($input as $data) {
            RadiologyCategory::create($data);
        }
    }
}
