<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelFactoryLine
 *
 * @property int $id
 * @property string $field
 * @property mixed|null|null $php
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine wherePhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FactoryLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FactoryLine extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-grip-lines';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model factory lines';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The lines of model factory';

    /**
     * @var string|null
     */
    public ?string $parent = Factory::class;

    /**
     * @var array
     */
    public static array $schema = [
        'field' => [
            'string',
            'possibleTable' => 'model_fields:name',
            'info' => 'The factory line field',
            'regexp' => '^\w*$',
        ],
        'php' => [
            'pjs',
            'nullable' => true,
            'info' => 'The factory line value',
        ],
    ];
}
