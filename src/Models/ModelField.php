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
    /**
     * @var string
     */
    public string $modelIcon = 'far fa-comment-dots';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model fields';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model fields';

    /**
     * @var string|null
     */
    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'name' => [
            'string',
            'info' => 'Field name',
            'regexp' => '^\w*$',
            'possibleTable' => 'model_fields:name',
            'when_value_is' => [
                'text' => ['type' => 'text', 'type_parameters' => [], 'cast' => 'string'],
                'info' => ['type' => 'text', 'type_parameters' => [], 'cast' => 'string'],
                'price' => ['type' => 'float', 'type_parameters' => [12, 2], 'cast' => 'float'],
                'amount' => ['type' => 'float', 'type_parameters' => [8, 2], 'cast' => 'float'],
                'email' => ['type' => 'string', 'nullable' => true, 'type_parameters' => [], 'cast' => 'string'],
                'phone' => ['type' => 'string', 'type_parameters' => [64], 'nullable' => true, 'cast' => 'string'],
                'description' => ['type' => 'text', 'nullable' => true, 'type_parameters' => [], 'cast' => 'string'],
                'order' => ['type' => 'integer', 'default' => 0, 'has_default' => true, 'type_parameters' => [], 'cast' => 'integer'],
                'active' => ['type' => 'boolean', 'default' => true, 'type_parameters' => [], 'cast' => 'boolean'],

                'is_*' => ['type' => 'boolean', 'default' => 1, 'has_default' => true, 'type_parameters' => [], 'cast' => 'boolean'],
                'price_*' => ['type' => 'float', 'type_parameters' => [12, 2], 'cast' => 'float'],
                'amount_*' => ['type' => 'float', 'type_parameters' => [8, 2], 'cast' => 'float'],
                '*_price' => ['type' => 'float', 'type_parameters' => [12, 2], 'cast' => 'float'],
                '*_amount' => ['type' => 'float', 'type_parameters' => [8, 2], 'cast' => 'float'],
                '*_num' => ['type' => 'integer', 'type_parameters' => [], 'cast' => 'integer'],
                '*_id' => ['type' => 'integer', 'type_parameters' => [], 'cast' => 'integer'],
                '*_at' => ['type' => 'timestamp', 'nullable' => true, 'type_parameters' => [], 'cast' => 'datetime'],
                '*_text' => ['type' => 'longText', 'nullable' => true, 'type_parameters' => [], 'cast' => 'string'],
                '*_count' => ['type' => 'integer', 'default' => 0, 'has_default' => true, 'type_parameters' => [], 'cast' => 'integer'],
                '*_description' => ['type' => 'mediumText', 'nullable' => true, 'type_parameters' => [], 'cast' => 'string'],
            ]
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
            'when_value_is' => [
                'bigIncrements' => ['cast' => 'integer'],
                'bigInteger' => ['cast' => 'integer'],
                'binary' => ['cast' => 'string'],
                'boolean' => ['cast' => 'boolean'],
                'char' => ['cast' => 'string'],
                'dateTimeTz' => ['cast' => 'datetime'],
                'dateTime' => ['cast' => 'datetime'],
                'date' => ['cast' => 'date'],
                'decimal' => ['cast' => 'decimal'],
                'double' => ['cast' => 'double'],
                'enum' => ['cast' => 'string'],
                'float' => ['cast' => 'float'],
                'foreignId' => ['cast' => 'integer'],
                'increments' => ['cast' => 'integer'],
                'integer' => ['cast' => 'integer'],
                'ipAddress' => ['cast' => 'string'],
                'json' => ['cast' => 'json'],
                'jsonb' => ['cast' => 'json'],
                'lineString' => ['cast' => 'string'],
                'longText' => ['cast' => 'string'],
                'macAddress' => ['cast' => 'string'],
                'mediumIncrements' => ['cast' => 'integer'],
                'mediumInteger' => ['cast' => 'integer'],
                'mediumText' => ['cast' => 'string'],
                'multiLineString' => ['cast' => 'string'],
                'set' => ['cast' => 'string'],
                'smallIncrements' => ['cast' => 'integer'],
                'smallInteger' => ['cast' => 'integer'],
                'string' => ['cast' => 'string'],
                'text' => ['cast' => 'string'],
                'timestampTz' => ['cast' => 'datetime'],
                'timestamp' => ['cast' => 'datetime'],
                'tinyIncrements' => ['cast' => 'integer'],
                'tinyInteger' => ['cast' => 'integer'],
                'tinyText' => ['cast' => 'string'],
                'unsignedBigInteger' => ['cast' => 'integer'],
                'unsignedDecimal' => ['cast' => 'decimal'],
                'unsignedInteger' => ['cast' => 'integer'],
                'unsignedMediumInteger' => ['cast' => 'integer'],
                'unsignedSmallInteger' => ['cast' => 'integer'],
                'unsignedTinyInteger' => ['cast' => 'integer'],
                'year' => ['cast' => 'integer'],
            ],
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
        'type_parameters' => [
            'array',
            'taggable' => true,
            'info' => 'The field type parameters',
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
        'comment' => [
            'string',
            'nullable' => true,
            'info' => 'The table field comment',
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
    ];
}
