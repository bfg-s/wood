<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class ModelField extends ModelTopic
{
    public string $icon = 'fas fa-text';

    public ?string $name = 'Model fields';

    public ?string $description = 'The model fields';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => ['string', 'name' => 'Field name'],
        'type' => [
            'string',
            'default' => 'string',
            'name' => 'Field type',
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
        'type_details' => 'array',
        'cast' => [
            'string',
            'default' => 'string',
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
        'hidden' => ['bool', 'default' => false],
        'nullable' => ['bool', 'default' => false],
        'unique' => ['bool', 'default' => false],
        'unsigned' => ['bool', 'default' => false],
        'primary' => ['bool', 'default' => false],
        'index' => ['bool', 'default' => false],
        'constrained' => ['string', 'nullable' => true],
        'cascade_on_update' => ['bool', 'default' => true],
        'cascade_on_delete' => ['bool', 'default' => true],
        'null_on_delete' => ['bool', 'default' => false],
        'default' => ['string', 'nullable' => true],
        'comment' => ['string', 'nullable' => true]
    ];
}
