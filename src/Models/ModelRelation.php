<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\ModelRelation
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $able
 * @property string $foreign
 * @property bool $with
 * @property bool $with_count
 * @property bool $nullable
 * @property bool $cascade_on_update
 * @property bool $cascade_on_delete
 * @property bool $null_on_delete
 * @property string $reverse_name
 * @property string $reverse_type
 * @property int $related_model_id
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Bfg\Wood\Models\Model|null $related_model
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
    public string $icon = 'fas fa-network-wired';

    public ?string $name = 'Model relations';

    public ?string $description = 'The model relations';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => [
            'string',
            'regexp' => '^\w*$',
            'info' => 'The relation name',
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
        ],
        'able' => [
            'string',
            'nullable' => true,
            'info' => 'Able name for morph relations',
        ],
        'with' => [
            'bool',
            'default' => false,
            'info' => 'Always load with this relation',
        ],
        'with_count' => [
            'bool',
            'default' => false,
            'info' => 'Always load with count of this relation rows',
        ],
        'nullable' => [
            'bool',
            'default' => false,
            'info' => 'Is nullable relation',
        ],
        'cascade_on_update' => [
            'bool',
            'default' => true,
            'info' => 'Is cascade on update',
        ],
        'cascade_on_delete' => [
            'bool',
            'default' => true,
            'info' => 'Is cascade on delete',
        ],
        'null_on_delete' => [
            'bool',
            'default' => false,
            'info' => 'Is set null on delete',
        ],
        'related_model' => [
            'select' => 'class', // select - modifier, class - field name for selection
            'info' => 'Related relation model',
        ],
        'reverse_name' => [
            'string',
            'info' => 'Reverse related relation name',
        ],
        'reverse_type' => [
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
            'info' => 'Reverse related relation type',
        ],
    ];

    public function related_model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'related_model_id');
    }
}
