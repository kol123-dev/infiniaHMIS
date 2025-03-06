<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    /**
     * Listen to the Setting updated event.
     */
    public function updated(Setting $setting): void
    {
        Setting::flushQueryCache();
    }

    /**
     * Listen to the Setting created event.
     */
    public function created(Setting $setting): void
    {
        Setting::flushQueryCache();
    }
}
