<?php

namespace Bfg\Wood\Generators\ModelGenerator;

use Bfg\Wood\Generators\GeneratorAbstract;
use Bfg\Wood\Generators\ModelGenerator;
use Bfg\Wood\Models\Topic;
use Illuminate\Support\Collection;

/**
 * @property-read ModelGenerator $parent
 */
class ModelFactoryGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return [];
    }

    /**
     * Handle generator process
     * @param  Topic  $topic
     * @return void
     */
    protected function handle(Topic $topic): void
    {
        // TODO: Implement handle() method.
    }
}
