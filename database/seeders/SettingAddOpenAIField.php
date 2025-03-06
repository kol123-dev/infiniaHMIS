<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingAddOpenAIField extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create(['key' => 'open_ai_enable', 'value' => 0]);
        Setting::create(['key' => 'open_ai_key','value' => null]);
    }
}
