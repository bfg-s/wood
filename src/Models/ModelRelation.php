<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Wood\Casts\AnonymousClassCast;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * Bfg\Wood\Models\ModelRelation
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $relation_class
 * @property string|null $able
 * @property string $foreign
 * @property string $foreign_id
 * @property bool $with
 * @property bool $with_count
 * @property bool $nullable
 * @property bool $cascade_on_update
 * @property bool $cascade_on_delete
 * @property bool $null_on_delete
 * @property string $reverse_name
 * @property string $reverse_type
 * @property string $reverse_type_class
 * @property int $related_model_id
 * @property int $order
 * @property int $model_id
 * @property AnonymousClassSubject $pivot_migration_class
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Bfg\Wood\Models\Model|null $related_model
 * @property-read \Bfg\Wood\Models\Model|null $model
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereAble($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereCascadeOnDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereCascadeOnUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereForeign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereNullOnDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereNullable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereRelatedModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereReverseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereReverseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereWith($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelRelation whereWithCount($value)
 * @mixin \Eloquent
 */
class ModelRelation extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-network-wired';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model relations';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model relations';

    /**
     * @var string|null
     */
    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'related_model' => [
            'select' => 'class', // select - modifier, class - field name for selection
            'info' => 'Related relation model',
            'copy_value_to' => [
                ['name'],
                ['able', 'able'],
            ],
        ],
        'name' => [
            'string',
            'regexp' => '^\w+$',
            'info' => 'The relation name',
            'nullable' => true,
        ],
        'type' => [
            'string',
            'default' => 'hasOne',
            'possible' => [
                'hasMany',
                'hasManyThrough',
                'hasOneThrough',
                'belongsToMany',
                'hasOne',
                'belongsTo',
                'morphTo',
                'morphOne',
                'morphMany',
                'morphToMany',
                'morphedByMany',
            ],
            'info' => 'The relation type',
            'when_value_is' => [
                'hasOne' => ['reverse_type' => 'hasMany'],
                'hasMany' => ['reverse_type' => 'hasOne'],
                'morphOne' => ['reverse_type' => 'morphTo'],
                'morphMany' => ['reverse_type' => 'morphTo'],
                'morphToMany' => ['reverse_type' => 'morphedByMany'],
                'belongsToMany' => ['reverse_type' => 'belongsToMany'],
                'hasManyThrough' => ['reverse_type' => 'hasOneThrough'],
                'hasOneThrough' => ['reverse_type' => 'hasManyThrough'],
                'belongsTo' => ['reverse_type' => 'hasMany'],
                'morphTo' => ['reverse_type' => 'morphMany'],
                'morphedByMany' => ['reverse_type' => 'morphToMany'],
            ],
            'pluralize' => [
                ['hasMany', 'plural', 'name'],
                ['hasManyThrough', 'plural', 'name'],
                ['hasOneThrough', 'singular', 'name'],
                ['belongsToMany', 'plural', 'name'],
                ['hasOne', 'singular', 'name'],
                ['belongsTo', 'plural', 'name'],
                ['morphTo', 'plural', 'name'],
                ['morphOne', 'singular', 'name'],
                ['morphMany', 'plural', 'name'],
                ['morphToMany', 'plural', 'name'],
                ['morphedByMany', 'plural', 'name'],
            ]
        ],
        'reverse_name' => [
            'string',
            'default' => '',
            'nullable' => true,
            'regexp' => '^\w*$',
            'info' => 'Reverse related relation name',
        ],
        'reverse_type' => [
            'string',
            'default' => 'hasMany',
            'nullable' => true,
            'possible' => [
                '',
                'hasMany',
                'hasManyThrough',
                'hasOneThrough',
                'belongsToMany',
                'hasOne',
                'belongsTo',
                'morphTo',
                'morphOne',
                'morphMany',
                'morphToMany',
                'morphedByMany',
            ],
            'info' => 'Reverse related relation type',
        ],
        'able' => [
            'string',
            'nullable' => true,
            'info' => 'Able name for morph relations',
            'if_is' => [
                ['type', 'morphTo'],
                ['type', 'morphOne'],
                ['type', 'morphMany'],
                ['type', 'morphToMany'],
                ['type', 'morphedByMany'],
            ],
        ],
        'with' => [
            'bool',
            'default' => 0,
            'info' => 'Always load with this relation',
        ],
        'with_count' => [
            'bool',
            'default' => 0,
            'info' => 'Always load with count of this relation rows',
        ],
        'nullable' => [
            'bool',
            'default' => 0,
            'info' => 'Is nullable relation',
        ],
        'cascade_on_update' => [
            'bool',
            'default' => 1,
            'info' => 'Is cascade on update',
        ],
        'cascade_on_delete' => [
            'bool',
            'default' => 1,
            'info' => 'Is cascade on delete',
        ],
        'null_on_delete' => [
            'bool',
            'default' => 0,
            'info' => 'Is set null on delete',
        ],
    ];

    /**
     * @return HasOne
     */
    public function related_model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'related_model_id');
    }

    /**
     * @return HasOne
     */
    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }

    /**
     * @return string
     */
    public function getForeignAttribute(): string
    {
        return Str::snake(Str::singular($this->model()->first()->table())) . '_id';
    }

    /**
     * @return string
     */
    public function getForeignIdAttribute(): string
    {
        return Str::snake(Str::singular($this->name)) . '_id';
    }

    /**
     * @return string
     */
    public function getRelationClassAttribute(): string
    {
        return config(
            "wood.relation_types.{$this->type}.class",
            HasOne::class
        );
    }

    /**
     * @param $value
     * @return string
     */
    public function getNameAttribute($value): string
    {
        if (!$value && $model = $this->related_model) {
            $value = Str::singular(Str::snake(class_basename($model->class->class)));
        }

        if ($this->type) {

            $cfg = config("wood.relation_types." . $this->type);
            return call_user_func([Str::class, $cfg['declinations']], $value ?: '');
        }
        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function getReverseNameAttribute($value): string
    {
        $value = $value ?: $this->model()->first()->table();

        if ($this->type) {

            if ($value === 'null') {
                $value = $this->model()->first()->table();
            }

            $reverse_type = $this->reverse_type ?: config("wood.relation_types." . $this->type . ".reverses");
            $cfg = config("wood.relation_types." . $reverse_type);
            return Str::camel(call_user_func([Str::class, $cfg['declinations']], $value));
        }

        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function getAbleAttribute($value): string
    {
        $prep = $value ? "" : " able";
        return Str::singular($value ?: $this->model()->first()->table()) . $prep;
    }

    /**
     * @param $value
     * @return string
     */
    public function getReverseTypeAttribute($value): string
    {
        if ($this->type) {

            return $value ?: config("wood.relation_types." . $this->type . ".reverses");
        }

        return $value ?: 'hasOne';
    }

    /**
     * @param $value
     * @return string
     */
    public function getReverseTypeClassAttribute(): string
    {
        $reverse_type = $this->reverse_type ?: config("wood.relation_types." . $this->type . ".reverses");

        return config("wood.relation_types." . $reverse_type . '.class', HasOne::class);
    }

    /**
     * @return AnonymousClassSubject
     */
    public function getPivotMigrationClassAttribute(): AnonymousClassSubject
    {
        $model = $this->model()->first();
        $table = $model->table() . '_' . $this->name;

        $date = config('wood.migration_prepend', '2023_01_01');
        return (new AnonymousClassCast())->get(
            $this,
            'migration_class',
            database_path(
                "migrations/{$date}_999999"
                ."_create_".$table."_table.php"
            ),
            $this->attributes
        );
    }
}
