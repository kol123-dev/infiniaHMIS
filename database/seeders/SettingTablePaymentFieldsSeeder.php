<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTablePaymentFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Stripe
        Setting::create(['key' => 'stripe_enable', 'value' => null]);
        Setting::create(['key' => 'stripe_key', 'value' => null]);
        Setting::create(['key' => 'stripe_secret', 'value' => null]);

        // Paypal
        Setting::create(['key' => 'paypal_enable', 'value' => null]);
        Setting::create(['key' => 'paypal_client_id', 'value' => null]);
        Setting::create(['key' => 'paypal_secret', 'value' => null]);
        Setting::create(['key' => 'paypal_mode', 'value' => null]);

        // RazorPay
        Setting::create(['key' => 'razorpay_enable', 'value' => null]);
        Setting::create(['key' => 'razorpay_key', 'value' => null]);
        Setting::create(['key' => 'razorpay_secret', 'value' => null]);
    }
}
