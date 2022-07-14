<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelProperty extends ModelTopic
{
    public string $icon = 'fas fa-id-card';

    public ?string $name = 'Model properties';

    public ?string $description = 'The model properties';

    public ?string $parent = Model::class;

    public static array $schema = [
        'modifier' => ['enum' => ['public', 'protected', 'private'], 'default' => 'public'],
        'name' => 'string',
        'value' => 'any',
    ];
}
