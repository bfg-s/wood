<?php

namespace Bfg\Wood;

use Bfg\Wood\Commands\WoodInstallCommand;
use Bfg\Wood\Commands\WoodRunCommand;
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
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wood.php',
            'wood'
        );
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/wood.php' => config_path('wood.php')
        ], 'wood-config');

        config(Arr::dot([
            'wood' => [
                'driver' => 'sqlite',
                'url' => null,
                'database' => database_path('wood.sqlite'),
                'prefix' => '',
                'foreign_key_constraints' => true
            ]
        ], 'database.connections.'));

        config(Arr::dot([
            'wood' => config('wood.connection')
        ], 'database.connections.'));

        $this->commands([
            WoodInstallCommand::class,
            WoodRunCommand::class,
        ]);

        $this->app->singleton(ClassFactory::class, function () {
            return new ClassFactory();
        });
    }
}

