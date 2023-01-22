<?php

namespace Bfg\Wood;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class WoodProvider extends ServiceProvider
{
    protected array $observers = [];

    protected array $listen = [];

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

        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners, SORT_REGULAR) as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
