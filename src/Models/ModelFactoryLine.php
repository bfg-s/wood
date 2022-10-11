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
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine wherePhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelFactoryLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelFactoryLine extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-industry';

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
    public ?string $parent = Model::class;

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
