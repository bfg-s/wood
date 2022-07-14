<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class SeedRow extends ModelTopic
{
    public string $icon = 'fas fa-grip-lines';

    public ?string $name = 'Seed rows';

    public ?string $description = 'The rows of seeder';

    /**
     * @var array
     */
    public static array $schema = [
        'row' => 'array',
    ];
}
