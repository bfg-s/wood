<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Models\Seed;
use Bfg\Wood\Models\Topic;
use Database\Seeders\DatabaseSeeder;
use ErrorException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * @mixin Seed
 */
class SeedGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Seed::all();
    }

    protected function basic()
    {
        $this->class->extends(Seeder::class);
    }

    protected function run()
    {
        $method = $this->class->publicMethod('run')
            ->clear();

        $method->comment(function (DocSubject $subject) {
            $subject->name('Run the database seeds.');
            $subject->tagReturn('void');
        });

        foreach ($this->rows as $row) {

            $method->line()->staticCall(
                Comcode::useIfClass($this->model->class->class, $this->class),
                'create',
                $row->row
            );
        }
    }

    /**
     * @throws ErrorException
     */
    protected function finish(): void
    {
        $class = app(ClassFactory::class)->class(
            DatabaseSeeder::class
        );

        foreach (Seed::all() as $item) {

            $class->publicMethod('run')
                ->row($item->class->class)
                ->var('this')
                ->call(
                    Comcode::useIfClass($item->class->class, $class) . '::class'
                );
        }

    }
}
