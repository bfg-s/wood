<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Core\ResponseEvent;
use Bfg\Wood\Models\Controller;
use Bfg\Wood\Models\ControllerMethod;
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
 * @mixin Controller
 */
class ControllerGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Controller::all();
    }

    protected function mainController()
    {
        $this->class->extends(\App\Http\Controllers\Controller::class);

        foreach ($this->methods()->get() as $item) {
            /** @var ControllerMethod $item */
            $method = $this->class
                ->publicMethod($item->row);
            $prop = [];
            if ($item->event_id) {
                /** @var Event $event */
                $event = $item->event()->first();
                if ($event) {
                    $var = lcfirst(class_basename($event->class->class));
                    $method->expectParams(
                        [$var, null, $event->class->class]
                    );
                    $method->row('Call event')->func('event', php()->var($var));
                    $method->return()->var($var)->response();
                    $prop = [$event->class->class, $var];
                }
            }
            $method->comment(function (DocSubject $subject) use ($prop) {
                if ($prop) {
                    $subject->tagParam(...$prop);
                    $subject->tagReturn('\\Illuminate\\Http\\Response|\\Illuminate\\Contracts\\Routing\\ResponseFactory');
                }
            });
        }
    }
}
