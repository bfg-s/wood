<?php

namespace Bfg\Wood;

use Bfg\Wood\Models\Config;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\Observer;
use Bfg\Wood\Models\Request;
use Bfg\Wood\Models\Resource;
use Bfg\Wood\Models\Seed;

class WoodCore
{
    protected array $topics = [
        Config::class,
        Model::class,
        Seed::class,
        Observer::class,
        Request::class,
        Resource::class,
    ];

    public function getTopics(): array
    {
        return $this->topics;
    }
}
