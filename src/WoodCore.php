<?php

namespace Bfg\Wood;

use Bfg\Wood\Models\Config;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\Observer;
use Bfg\Wood\Models\Request;
use Bfg\Wood\Models\Resource;
use Bfg\Wood\Models\Seed;
use Bfg\Wood\Models\Topic;

class WoodCore
{
    /**
     * @var array|string[]
     */
    protected array $topics = [
        'config' => Config::class,
        'model' => Model::class,
        'seed' => Seed::class,
        'observer' => Observer::class,
        'request' => Request::class,
        'resource' => Resource::class,
    ];

    /**
     * @return array|Topic[]
     */
    public function getTopics(): array
    {
        return array_values($this->topics);
    }

    /**
     * @param  string  $topicClass
     * @param  string|null  $part
     * @return $this
     */
    public function addTopic(string $topicClass, string $part = null): static
    {
        if ($part) {
            $this->topics[$part] = $topicClass;
        } else {
            $this->topics[] = $topicClass;
        }

        return $this;
    }
}
