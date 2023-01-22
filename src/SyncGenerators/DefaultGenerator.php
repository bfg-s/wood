<?php

namespace Bfg\Wood\SyncGenerators;

use Bfg\Wood\Models\Topic;
use Illuminate\Support\Collection;

class DefaultGenerator extends SyncGeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return collect(true);
    }
}
