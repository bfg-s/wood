<?php

namespace Bfg\Wood\Commands;

use Bfg\Wood\Models\Php;
use Bfg\Wood\Models\Topic;
use Bfg\Wood\ModelTopic;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Wood;

class WoodInstallCommand extends Command
{
    protected $signature = "wood:install {--f|force : Recreate tables}";

    protected $description = "Install wood schema";

    /**
     * @var Builder
     */
    protected Builder $connection;

    /**
     * @var array
     */
    protected array $created = [];

    public function handle(): int
    {
        $dbFile = database_path('wood.sqlite');

        if (! is_file($dbFile)) {

            file_put_contents($dbFile, '');
        }

        $this->connection = Schema::connection('wood');

        $this->defaultTables();

        $this->prepareModels(
            Wood::getTopics()
        );

        collect(ModelTopic::$list)->map(
            fn ($topic) => $this->createTable(app($topic))
        );

        $this->info('Finished!');

        return 0;
    }

    protected function defaultTables()
    {
        if (!$this->connection->hasTable('topics')) {

            $this->connection->create('topics', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('icon')->default('fas fa-folder');
                $table->string('table');
                $table->string('topic');
                $table->string('parent_topic')->nullable();
                $table->json('settings');
                $table->json('schema');
                $table->timestamps();
            });

            $this->info('Topics table, created!');
        }

        if (!$this->connection->hasTable('php')) {

            $this->connection->create('php', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['class', 'interface', 'trait']);
                $table->string('file');
                $table->bigInteger('inode');
                $table->string('name');
                $table->json('methods')->nullable();
            });

            $this->info('PHP table, created!');
        }

        $this->freshPhpTable();
    }

    protected function freshPhpTable()
    {
//        foreach (get_declared_classes() as $declared_class) {
//
//            Php::createOrUpdatePhp($declared_class);
//        }
//
//        foreach (get_declared_traits() as $declared_trait) {
//
//            Php::createOrUpdatePhp($declared_trait);
//        }
//
//        foreach (get_declared_interfaces() as $declared_interface) {
//
//            Php::createOrUpdatePhp($declared_interface);
//        }

        $res = get_declared_classes();
        $autoloaderClassName = '';
        foreach ( $res as $className) {
            if (str_starts_with($className, 'ComposerAutoloaderInit')) {
                $autoloaderClassName = $className;
                break;
            }
        }
        $classLoader = $autoloaderClassName::getLoader();

        foreach ($classLoader->getClassMap() as $class => $path) {
            $path = str_replace(base_path(), '', realpath($path));
            if (
                ! str_starts_with($path, '/vendor/doctrine')
                && ! str_starts_with($path, '/vendor/psy')
            ) {
                Php::createOrUpdatePhp($class);
            }
        }

        Php::where('inode', 0)->delete();
    }

    protected function createTable(ModelTopic $topic)
    {
        $table = $topic->getTable();

        if ($this->option('force')) {

            $this->connection->dropIfExists($table);
        }

        if (! $this->connection->hasTable($table)) {

            $this->connection->create(
                $topic->getTable(),
                fn (Blueprint $table)
                => $this->generateTableFields($table, $topic)
            );

            foreach ($topic::seeds() as $seed) {

                $topic->create($seed);
            }

            $this->info(
                ucfirst(str_replace('_', ' ', $table))
                . ' schema, created!'
            );
        }

        Topic::createOrUpdateTopic($topic);
    }

    protected function generateTableFields(Blueprint $table, ModelTopic $topic)
    {
        $table->id();
        foreach ($topic::$schema as $name => $item) {
            if (! $item['schema']) {
                $tableResult = $table->{$item['type']}($name, ...$item['params']);
                foreach ($item['methods'] as $method => $values) {
                    $tableResult->{$method}(...((array) $values));
                }
            }
        }
        $table->timestamps();
    }

    protected function prepareModels(array $models): Collection
    {
        return collect($models)
            ->map(fn($model) => app($model))
            ->filter(fn($model) => $model instanceof ModelTopic);
    }
}
