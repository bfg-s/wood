<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Observer extends ModelTopic
{
    public string $icon = 'fas fa-eye';

    public ?string $name = 'Model observers';

    public ?string $description = 'The model observers';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => 'class',
        'model' => [],
        'events' => ['array', 'variants' => [
            'retrieved', 'creating', 'created', 'updating', 'updated',
            'saving', 'saved', 'restoring', 'restored', 'replicating',
            'deleting', 'deleted', 'forceDeleted',
        ]],
    ];

    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }
}
