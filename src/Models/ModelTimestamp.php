<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelTimestamp extends ModelTopic
{
    public string $icon = 'fas fa-alarm-clock';

    public ?string $name = 'Model timestamps';

    public ?string $description = 'The model timestamps';

    public ?string $parent = Model::class;

    public static array $schema = [
        'created' => ['bool', 'default' => true],
        'updated' => ['bool', 'default' => true],
        'deleted' => ['bool', 'default' => false],
    ];
}
