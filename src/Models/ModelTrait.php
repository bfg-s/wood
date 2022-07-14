<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelTrait extends ModelTopic
{
    public string $icon = 'fas fa-file-contract';

    public ?string $name = 'Model traits';

    public ?string $description = 'The model traits';

    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'class' => 'trait',
    ];
}
