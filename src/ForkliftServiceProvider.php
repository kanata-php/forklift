<?php

namespace Kanata\Forklift;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ForkliftServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forklifter'),
        ], 'forklifter-resources');

        Livewire::component('forklifter-dropdown', ForkLifterDropdown::class);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forklift');
    }
}
