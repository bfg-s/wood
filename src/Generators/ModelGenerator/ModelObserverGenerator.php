<?php

namespace Bfg\Wood\Generators\ModelGenerator;

use App\Providers\AppServiceProvider;
use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Generators\GeneratorAbstract;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\Topic;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @mixin Model
 */
class ModelObserverGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Model::all();
    }

    protected function classes()
    {
        foreach ($this->observers as $observer) {
            foreach ($observer->events as $event) {
                $method = $observer->class->publicMethod([
                    'void', $event
                ]);
                $baseName = class_basename($this->class->class);
                $paramName = Str::snake($baseName);
                $method->expectParams([
                    $paramName,
                    null,
                    Comcode::useIfClass($this->class->class, $observer->class),
                ]);
                $method->comment(function (DocSubject $subject) use ($baseName, $event, $observer, $paramName) {
                    $subject->name("Handle the $baseName \"$event\" event.");
                    $subject->tagParam(
                        Comcode::useIfClass($this->class->class, $observer->class),
                        $paramName
                    );
                    $subject->tagReturn('void');
                });
            }
        }
    }

    protected function provider()
    {
        $provider = app(ClassFactory::class)
            ->class(AppServiceProvider::class);

        $method = $provider->publicMethod(['void', 'boot']);
        foreach ($this->observers as $observer) {

            $method->row($this->class->class . ' Observer ' . $observer->id)
                ->staticCall(
                    $this->class->class,
                    'observe',
                    Comcode::useIfClass(
                        $observer->class->class,
                        $provider
                    ) . "::class"
                );
        }
    }
}
