<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelConstant
 *
 * @property int $id
 * @property string $modifier
 * @property string $name
 * @property mixed|null|null $value
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereModifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelConstant whereValue($value)
 * @mixin \Eloquent
 */
class ModelConstant extends ModelTopic
{
    public string $icon = 'fas fa-toolbox';

    public ?string $name = 'Model constants';

    public ?string $description = 'The model constants';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => [
            'string',
            'info' => 'Constant name',
        ],
        'value' => [
            'pjs',
            'info' => 'Constant value',
        ],
        'modifier' => [
            'enum' => ['public', 'protected', 'private'],
            'default' => 'public',
            'info' => 'Constant modifier',
        ],
    ];
}
