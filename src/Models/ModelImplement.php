<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelImplement extends ModelTopic
{
    public string $icon = 'fas fa-award';

    public ?string $name = 'Model interfaces';

    public ?string $description = 'The model interfaces';

    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'class' => 'interface',
    ];
}
