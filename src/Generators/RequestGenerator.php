<?php

namespace Bfg\Wood\Generators;

use Bfg\Wood\Generators\RequestGenerator\RequestRuleGenerator;
use Bfg\Wood\Models\Topic;
use Illuminate\Support\Collection;

class RequestGenerator extends GeneratorAbstract
{
    /**
     * @var array
     */
    protected array $prependChild = [
        RequestRuleGenerator::class
    ];

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
