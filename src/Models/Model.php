<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\Casts\AnonymousClassCast;
use Bfg\Wood\Generators\PivotMigrationsGenerator;
use Bfg\Wood\Generators\ModelGenerator;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Bfg\Wood\Models\Model
 *
 * @property int $id
 * @property ClassSubject $class
 * @property AnonymousClassSubject $migration_class
 * @property bool $auth
 * @property bool $migration
 * @property bool $increment
 * @property string $table
 * @property string $foreign
 * @property string $foreign_id
 * @property bool $created
 * @property bool $updated
 * @property bool $deleted
 * @property int $factory_count
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\FactoryLine[] $factory_lines
 * @property-read int|null $factory_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelField[] $fields
 * @property-read int|null $fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelImplement[] $implements
 * @property-read int|null $implements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelRelation[] $relations
 * @property-read int|null $relations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelRelation[] $related
 * @property-read int|null $related_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelTrait[] $traits
 * @property-read int|null $traits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\ModelObserver[] $observers
 * @property-read int|null $observers_count
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
    /**
     * @var array|string[]
     */
    protected static array $generators = [
        'general' => ModelGenerator::class,
        'belongsToManyMigrations' => PivotMigrationsGenerator::class,
    ];

    /**
     * @var string|null
     */
    public ?string $modelName = 'Models';

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-cube';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'Database models of laravel';

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
        'foreign' => [
            'string',
            'default' => 'id',
            'if_not' => 'increment',
            'regexp' => '^\w*$',
            'info' => 'The foreign field',
        ],
        'increment' => [
            'bool',
            'default' => true,
            'invisible' => true,
            'info' => 'The increment',
        ],
        'auth' => [
            'bool',
            'default' => false,
            'info' => 'Auth extension',
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
        'fields' => [
            'info' => 'Fields',
        ],
        'relations' => [
            'info' => 'Relations',
        ],
        'observers' => [
            'info' => 'Observers',
        ],
        'traits' => [
            'default' => [['class' => HasFactory::class]],
            'info' => 'Traits',
        ],
        'implements' => [
            'info' => 'Implements',
        ],
        'migration' => [
            'bool',
            'name' => 'Migration',
            'default' => true,
            'info' => 'Automatically create migration',
        ],
    ];

    /**
     * @return HasMany
     */
    public function implements(): HasMany
    {
        return $this->hasMany(ModelImplement::class);
    }

    /**
     * @return HasMany
     */
    public function traits(): HasMany
    {
        return $this->hasMany(ModelTrait::class);
    }

    /**
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(ModelField::class);
    }

    /**
     * @return HasMany
     */
    public function relations(): HasMany
    {
        return $this->hasMany(ModelRelation::class);
    }

    /**
     * @return HasMany
     */
    public function observers(): HasMany
    {
        return $this->hasMany(ModelObserver::class);
    }

    /**
     * @return HasMany
     */
    public function related(): HasMany
    {
        return $this->hasMany(ModelRelation::class, 'related_model_id', 'id');
    }

    /**
     * @return string
     */
    public function getTableAttribute(): string
    {
        return $this->table();
    }

    /**
     * @return string
     */
    public function getForeignIdAttribute(): string
    {
        return Str::singular($this->table()) . '_id';
    }

    /**
     * @return AnonymousClassSubject
     */
    public function getMigrationClassAttribute(): AnonymousClassSubject
    {
        $date = config('wood.migration_prepend', '2022_12_01');
        return (new AnonymousClassCast())->get(
            $this,
            'migration_class',
            database_path(
                "migrations/{$date}_"
                .str_repeat('0', 6 - strlen($this->order))
                .$this->order."_create_".$this->table()."_table.php"
            ),
            $this->attributes
        );
    }

    /**
     * @return string
     */
    public function table(): string
    {
        return strtolower(Str::snake(Str::singular(
            class_basename($this->class->class)
        )));
    }
}
