<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Model extends ModelTopic
{
    public ?string $name = 'Models';

    public string $icon = 'fas fa-cube';

    public ?string $description = 'Database models of laravel';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => ['class', 'unique' => true],
        'table' => ['string', 'nullable' => true],
        'auth' => ['bool', 'default' => false],
        'increment' => ['bool', 'default' => true],
        'foreign' => ['string', 'default' => 'id'],
        'implements' => [],
        'traits' => ['default' => [['class' => HasFactory::class]]],
        'fields' => [],
        'constants' => [],
        'relations' => [],
        'timestamp' => ['default' => []],
        'properties' => [],
        'factory' => [],
    ];

    public function implements(): HasMany
    {
        return $this->hasMany(ModelImplement::class);
    }

    public function traits(): HasMany
    {
        return $this->hasMany(ModelTrait::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ModelField::class);
    }

    public function constants(): HasMany
    {
        return $this->hasMany(ModelConstant::class);
    }

    public function relations(): HasMany
    {
        return $this->hasMany(ModelRelation::class);
    }

    public function timestamp(): HasOne
    {
        return $this->hasOne(ModelTimestamp::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(ModelProperty::class);
    }

    public function factory(): HasOne
    {
        return $this->hasOne(ModelFactory::class);
    }
}
