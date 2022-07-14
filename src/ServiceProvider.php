<?php

namespace Bfg\Wood;

use Bfg\Wood\Commands\WoodInstallCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register route settings.
     * @return void
     */
    public function register()
    {
        config(Arr::dot([
            'wood' => [
                'driver' => 'sqlite',
                'url' => null,
                'database' => database_path('wood.sqlite'),
                'prefix' => '',
                'foreign_key_constraints' => true
            ]
        ], 'database.connections.'));
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        $this->commands([
            WoodInstallCommand::class
        ]);
    }
}

