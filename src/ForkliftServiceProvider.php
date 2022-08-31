<?php

namespace Kanata\Forklift;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ForkliftServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forklift');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forklift'),
        ]);

        Livewire::component('forklifter-dropdown', ForklifterDropdown::class);
    }
}
