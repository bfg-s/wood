<?php

namespace Bfg\Wood;

use Illuminate\Support\ServiceProvider;

class WoodProvider extends ServiceProvider
{
    protected array $observers = [];

    /**
     * Register any application services.
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->observers as $model => $observer) {
            foreach ($observer as $item) {
                $model::observe($item);
            }
        }
    }
}
