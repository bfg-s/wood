<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelFactory extends ModelTopic
{
    public string $icon = 'fas fa-industry';

    public ?string $name = 'Model factories';

    public ?string $description = 'The model data factories';

    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'count' => ['int', 'default' => 1],
        'lines' => [],
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(ModelFactoryLine::class);
    }
}
