<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Models\Event;
use Bfg\Wood\Models\EventListener;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\Seed;
use Bfg\Wood\Models\Topic;
use Database\Seeders\DatabaseSeeder;
use ErrorException;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

/**
 * @mixin Event
 */
class EventGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Event::all();
    }

    protected function mainEvent()
    {
        $this->class
            ->publicMethod('__construct')
            ->comment(function (DocSubject $subject) {
                $subject->name('Create a new event instance.');
                $subject->tagReturn('void');
            });

        $this->class->trait(Dispatchable::class);
        $this->class->trait(InteractsWithSockets::class);
        $this->class->trait(SerializesModels::class);
    }

    protected function mainListeners()
    {
        /** @var EventListener[] $listeners */
        $listeners = $this->listeners()->get();

        foreach ($listeners as $listener) {

            $listener->class->use($this->class->class);

            $listener->class->publicMethod('handle')
                ->expectParams(['event', null, $this->class->class])
                ->comment(function (DocSubject $subject) {
                    $subject->name('Handle the event.');
                    $subject->tagParam($this->class->class, 'event');
                    $subject->tagReturn('void');
                });
        }
    }

    protected function finish(): void
    {
        $provider = app(ClassFactory::class)
            ->class("App\\Providers\\BfgWoodProvider");

        $listen = [];

        foreach (Event::all() as $item) {
            foreach ($item->listeners()->get() as $listener) {
                $listen[
                Comcode::useIfClass($item->class->class, $provider) . '::class'
                ][] = Comcode::useIfClass($listener->class->class, $provider) . '::class';
            }
        }

        $provider->protectedProperty(
            ['array', 'listen'], $listen
        );
    }
}
