<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelField
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property array $type_details
 * @property string $cast
 * @property bool $has_default
 * @property string|null $default
 * @property string|null $comment
 * @property bool $hidden
 * @property bool $nullable
 * @property bool $unique
 * @property int $unsigned
 * @property int $primary
 * @property bool $index
 * @property string|null $constrained
 * @property int $cascade_on_update
 * @property int $cascade_on_delete
 * @property int $null_on_delete
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereCascadeOnDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereCascadeOnUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereCast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereConstrained($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereHasDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereNullOnDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereNullable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereTypeDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereUnsigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelField extends ModelTopic
{
    public string $icon = 'far fa-comment-dots';

    public ?string $name = 'Model fields';

    public ?string $description = 'The model fields';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => [
            'string',
            'info' => 'Field name',
            'regexp' => '^\w*$',
            'possibleTable' => 'model_fields:name',
        ],
        'type' => [
            'string',
            'default' => 'string',
            'info' => 'Field type',
            'variants' => [
                'bigIncrements',
                'bigInteger',
                'binary',
                'boolean',
                'char',
                'dateTimeTz',
                'dateTime',
                'date',
                'decimal',
                'double',
                'enum',
                'float',
                'foreignId',
                'increments',
                'integer',
                'ipAddress',
                'json',
                'jsonb',
                'lineString',
                'longText',
                'macAddress',
                'mediumIncrements',
                'mediumInteger',
                'mediumText',
                'multiLineString',
                'set',
                'smallIncrements',
                'smallInteger',
                'string',
                'text',
                'timestampTz',
                'timestamp',
                'tinyIncrements',
                'tinyInteger',
                'tinyText',
                'unsignedBigInteger',
                'unsignedDecimal',
                'unsignedInteger',
                'unsignedMediumInteger',
                'unsignedSmallInteger',
                'unsignedTinyInteger',
                'year',
            ],
        ],
        'type_details' => [
            'array',
            'info' => 'The field type details',
            'variants' => [
                'Unsigned' => ['unsigned' => []],
                'Primary' => ['primary' => []],
                'Constrained' => ['constrained' => ''],
                'Cascade on update' => ['cascade_on_update' => []],
                'Cascade on delete' => ['cascade_on_delete' => []],
                'Null on delete' => ['null_on_delete' => []],
            ]
        ],
        'cast' => [
            'string',
            'default' => 'string',
            'info' => 'Field cast type',
            'possible' => [
                'string',
                'integer',
                'boolean',
                'timestamp',
                'array',
                'double',
                'float',
                'decimal: <digits>',
                'date',
                'datetime',
                'immutable_date',
                'immutable_datetime',
                'encrypted',
                'encrypted:array',
                'encrypted:collection',
                'encrypted:object',
                'collection',
                'object',
                'real',
            ]
        ],
        'hidden' => [
            'bool',
            'default' => false,
            'info' => 'Is hidden field',
        ],
        'nullable' => [
            'bool',
            'default' => false,
            'info' => 'Is nullable field',
        ],
        'unique' => [
            'bool',
            'default' => false,
            'info' => 'Is unique field',
        ],
        'index' => [
            'bool',
            'default' => false,
            'info' => 'Is index field',
        ],
        'has_default' => [
            'bool',
            'default' => false,
            'info' => 'Is field has default',
        ],
        'default' => [
            'string',
            'nullable' => true,
            'if_not' => 'has_default',
            'info' => 'The default field value',
        ],
        'comment' => [
            'string',
            'nullable' => true,
            'info' => 'The table field comment',
        ],
    ];
}
