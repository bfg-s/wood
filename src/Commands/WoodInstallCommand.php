<?php

namespace Bfg\Wood\Commands;

use Bfg\Wood\Models\Php;
use Bfg\Wood\Models\Topic;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Wood;

class WoodInstallCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:install
    {--p|php : With php}
    {--f|force : Recreate tables}
    ";

    /**
     * @var string
     */
    protected $description = "Install wood schema";

    /**
     * @var array
     */
    protected array $created = [];

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->defaultTables();

        $this->prepareModels(
            Wood::getTopics()
        );

        collect(ModelTopic::$list)->map(
            fn ($topic) => $this->createTable(app($topic))
        );

        $this->freshPhpTable(
            $this->option('php')
        );

        $this->info('Finished!');

        return 0;
    }

    /**
     * @return void
     */
    protected function defaultTables(): void
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
    }

    /**
     * @param  ModelTopic  $topic
     * @return void
     */
    protected function createTable(ModelTopic $topic): void
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

    /**
     * @param  Blueprint  $table
     * @param  ModelTopic  $topic
     * @return void
     */
    protected function generateTableFields(Blueprint $table, ModelTopic $topic): void
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

    /**
     * @param  array  $models
     * @return Collection
     */
    protected function prepareModels(array $models): Collection
    {
        return collect($models)
            ->map(fn($model) => app($model))
            ->filter(fn($model) => $model instanceof ModelTopic);
    }
}
