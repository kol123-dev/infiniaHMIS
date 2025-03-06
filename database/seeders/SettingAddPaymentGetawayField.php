<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingAddPaymentGetawayField extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         //FlutterWave Payment Keys
         Setting::create(['key' => 'flutterwave_enable','value' => null]);
         Setting::create(['key' => 'flutterwave_public_key','value' => null]);
         Setting::create(['key' => 'flutterwave_secret_key','value' => null]);
    }
}
