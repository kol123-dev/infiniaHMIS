<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingPhonePeFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RazorPay
        Setting::updateOrCreate(['key' => 'phone_pe_enable', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_merchant_id', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_merchant_user_id', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_env', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_salt_key', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_salt_index', 'value' => null]);
        Setting::updateOrCreate(['key' => 'phonepe_merchant_transaction_id', 'value' => null]);
    }
}
