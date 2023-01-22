<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
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
     * @var array
     */
    protected static array $lines = [];

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

        if ($this->factory) {

            $method->line()->staticCall(
                    Comcode::useIfClass($this->model->class->class, $this->class),
                    'factory',
                    $this->count
                )->create();
        }
    }

    protected function finish(): void
    {
        $class = app(ClassFactory::class)->class(
            DatabaseSeeder::class
        );

        $classes = [];

        foreach (Seed::all() as $item) {

            $classes[] = Comcode::useIfClass($item->class->class, $class) . '::class';
        }

        $class->publicMethod('run')
            ->row('Bfg seeders')
            ->var('this')
            ->call($classes);
    }
}
