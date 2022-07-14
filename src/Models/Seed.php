<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seed extends ModelTopic
{
    public string $icon = 'fas fa-seedling';

    public ?string $name = 'Seeders';

    public ?string $description = 'The laravel seeders';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => ['class', 'unique' => true],
        'model' => [],
        'rows' => [],
    ];

    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }

    public function rows(): HasMany
    {
        return $this->hasMany(SeedRow::class);
    }
}
