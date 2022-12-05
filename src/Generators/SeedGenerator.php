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

//    protected function insertInToDatabaseSeeder()
//    {
//        $file = database_path('seeders/DatabaseSeeder.php');
//
//        $file_content = file_get_contents($file);
//
//        $class = app(ClassFactory::class)->class(
//            DatabaseSeeder::class
//        );
//
//        if (! preg_match("/{" . class_basename($this->class->class) . "}::class/", $file_content)) {
//
//            static::$lines[] = "        \$this->call("
//            . Comcode::useIfClass($this->class->class, $class) .
//            "::class);";
//        }
//    }

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

    public function afterSave(): void
    {

//        $file = database_path('seeders/DatabaseSeeder.php');
//
//        $file_content = file_get_contents($file);
//
//        if (static::$lines) {
//            $ref = new \ReflectionClass(DatabaseSeeder::class);
//
//            $method = $ref->getMethod('run');
//
//            $method_text = file_lines_get_contents($file, $method->getEndLine(), $method->getStartLine());
//
//            $exploded_method = array_slice(
//                explode("\n", $method_text), 0, -2
//            );
//
//            $exploded_method = array_merge($exploded_method, array_reverse(static::$lines));
//
//            $exploded_method[] = '    }';
//            $exploded_method[] = '';
//
//            $new_file_content = str_replace(
//                $method_text, implode("\n", $exploded_method), $file_content
//            );
//
//            file_put_contents($file, $new_file_content);
//        }





//        $class = app(ClassFactory::class)->class(
//            DatabaseSeeder::class
//        );
//
//        foreach (Seed::all() as $item) {
//
//            $class->publicMethod('run')
//                ->row($item->class->class)
//                ->var('this')
//                ->call(
//                    Comcode::useIfClass($item->class->class, $class) . '::class'
//                );
//        }

    }
}
