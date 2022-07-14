<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resource extends ModelTopic
{
    public string $icon = 'fas fa-poll-h';

    public ?string $name = 'Resources';

    public ?string $description = 'The laravel resources';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => ['class', 'unique' => true],
    ];
}
