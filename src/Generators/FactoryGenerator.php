<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Models\Factory;
use Bfg\Wood\Models\FactoryLine;
use Bfg\Wood\Models\Topic;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Collection;

/**
 * @mixin Factory
 */
class FactoryGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Factory::all();
    }

    protected function basic()
    {
        $this->class->extends(
            \Illuminate\Database\Eloquent\Factories\Factory::class
        );
    }

    protected function definition()
    {
        $method = $this->class->publicMethod('definition');

        $method->return(php()->real($this->lines->mapWithKeys(
            fn (FactoryLine $line) => [$line->field => php()->rawForArray($line->php)]
        )->toArray()));

        $method->comment(function (DocSubject $subject) {
            $subject->name("Define the model's default state.");
            $subject->tagReturn('array<string, mixed>');
        });
    }
}
