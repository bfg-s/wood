<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ModelRelation extends ModelTopic
{
    public string $icon = 'fas fa-network-wired';

    public ?string $name = 'Model relations';

    public ?string $description = 'The model relations';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => 'string',
        'type' => ['string', 'default' => 'hasOne'],
        'able' => ['string', 'nullable' => true],
        'foreign' => ['string', 'default' => 'id'],
        'with' => ['bool', 'default' => false],
        'with_count' => ['bool', 'default' => false],
        'nullable' => ['bool', 'default' => false],
        'cascade_on_update' => ['bool', 'default' => true],
        'cascade_on_delete' => ['bool', 'default' => true],
        'null_on_delete' => ['bool', 'default' => false],
        'reverse' => ['nullable' => true],
        'related_model' => [],
    ];

    public function reverse(): HasOne
    {
        return $this->hasOne(ModelRelation::class, 'reverse_id');
    }

    public function related_model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'related_model_id');
    }
}
