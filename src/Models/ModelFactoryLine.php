<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelFactoryLine extends ModelTopic
{
    public string $icon = 'fas fa-grip-lines';

    public ?string $name = 'Model factory lines';

    public ?string $description = 'The lines of model factory';

    public ?string $parent = ModelFactory::class;

    /**
     * @var array
     */
    public static array $schema = [
        'field' => 'string',
        'value' => 'any',
        'php' => ['pjs', 'nullable' => true],
    ];
}
