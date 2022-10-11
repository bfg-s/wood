<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelProperty
 *
 * @property int $id
 * @property string $modifier
 * @property string $name
 * @property string|null $value
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereModifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelProperty whereValue($value)
 * @mixin \Eloquent
 */
class ModelProperty extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-id-card';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model properties';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model properties';

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
            'info' => 'The property name',
            'regexp' => '^\w*$',
        ],
        'value' => [
            'pjs',
            'info' => 'The property value',
        ],
        'modifier' => [
            'enum' => ['public', 'protected', 'private'],
            'default' => 'public',
            'info' => 'The property modifier',
        ],
    ];
}
