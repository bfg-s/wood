<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelConstant extends ModelTopic
{
    public string $icon = 'fas fa-dewpoint';

    public ?string $name = 'Model constants';

    public ?string $description = 'The model constants';

    public ?string $parent = Model::class;

    public static array $schema = [
        'modifier' => ['enum' => ['public', 'protected', 'private'], 'default' => 'public'],
        'name' => 'string',
        'value' => 'any',
    ];
}
