<?php

namespace App\Observers;

use App\Models\Module;

class ModuleObserver
{
    /**
     * Listen to the Module updated event.
     */
    public function updated(Module $module): void
    {
        Module::flushQueryCache();
    }

    /**
     * Listen to the Module created event.
     */
    public function created(Module $module): void
    {
        Module::flushQueryCache();
    }
}
