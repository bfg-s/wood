<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\Model
 *
 * @property int $id
 * @property mixed|null $class
 * @property bool $auth
 * @property bool $increment
 * @property string $foreign
 * @property bool $created
 * @property bool $updated
 * @property bool $deleted
 * @property int $factory_count
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelConstant[] $constants
 * @property-read int|null $constants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelFactoryLine[] $factory_lines
 * @property-read int|null $factory_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelField[] $fields
 * @property-read int|null $fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelImplement[] $implements
 * @property-read int|null $implements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelProperty[] $properties
 * @property-read int|null $properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelRelation[] $relations
 * @property-read int|null $relations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelTrait[] $traits
 * @property-read int|null $traits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Model newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereFactoryCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereForeign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereIncrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Model whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Model extends ModelTopic
{
    public ?string $name = 'Models';

    public string $icon = 'fas fa-cube';

    public ?string $description = 'Database models of laravel';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'unique' => true,
            'prepend' => "App\\Models\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Model class',
        ],
        'auth' => [
            'bool',
            'default' => false,
            'info' => 'Auth extension',
        ],
        'increment' => [
            'bool',
            'default' => true,
            'info' => 'The increment',
        ],
        'foreign' => [
            'string',
            'default' => 'id',
            'if_not' => 'increment',
            'regexp' => '^\w*$',
            'info' => 'The foreign field',
        ],
        'created' => [
            'bool',
            'name' => 'Created at',
            'default' => true,
            'info' => 'Use the "created_at" field',
        ],
        'updated' => [
            'bool',
            'name' => 'Updated at',
            'default' => true,
            'info' => 'Use the "updated_at" field',
        ],
        'deleted' => [
            'bool',
            'name' => 'Soft delete',
            'default' => false,
            'info' => 'Use the SoftDelete and "deleted_at" field',
        ],
        'factory_count' => [
            'integer',
            'default' => 0,
            'info' => 'The count of factory creating rows',
            'regexp' => '^\d*$',
        ],
        'constants' => [
            'info' => 'The constants for the model',
        ],
        'fields' => [
            'info' => 'The fields for the model',
        ],
        'relations' => [
            'info' => 'The relations for the model',
        ],
        'traits' => [
            'default' => [['class' => HasFactory::class]],
            'info' => 'The traits for the model',
        ],
        'implements' => [
            'info' => 'The interfaces for the model',
        ],
        'properties' => [
            'info' => 'The properties for the model',
        ],
        'factory_lines' => [
            'info' => 'The lines of factory for the model',
        ],
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

    public function properties(): HasMany
    {
        return $this->hasMany(ModelProperty::class);
    }

    public function factory_lines(): HasMany
    {
        return $this->hasMany(ModelFactoryLine::class);
    }
}
