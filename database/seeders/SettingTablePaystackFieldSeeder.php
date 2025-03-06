<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTablePaystackFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // RazorPay
         Setting::create(['key' => 'paystack_enable', 'value' => null]);
         Setting::create(['key' => 'paystack_public_key', 'value' => null]);
         Setting::create(['key' => 'paystack_secret_key', 'value' => null]);
    }
}
