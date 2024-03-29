<?php

namespace Bfg\Wood;

use Bfg\Wood\Commands\WoodBuildCommand;
use Bfg\Wood\Commands\WoodImportCommand;
use Bfg\Wood\Commands\WoodInstallCommand;
use Bfg\Wood\Commands\WoodParseAndRunCommand;
use Bfg\Wood\Commands\WoodParseCommand;
use Bfg\Wood\Commands\WoodRunCommand;
use Bfg\Wood\Commands\WoodSyncCommand;
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

        if (class_exists("\\App\\Providers\\BfgWoodProvider")) {
            $this->app->register("\\App\\Providers\\BfgWoodProvider");
        }
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
            WoodRunCommand::class,
            WoodSyncCommand::class,
            WoodBuildCommand::class,
            WoodImportCommand::class,
            WoodInstallCommand::class,
        ]);

        $this->app->singleton(ClassFactory::class, function () {
            return new ClassFactory();
        });
    }
}

