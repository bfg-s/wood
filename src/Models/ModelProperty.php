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
    public string $icon = 'fas fa-id-card';

    public ?string $name = 'Model properties';

    public ?string $description = 'The model properties';

    public ?string $parent = Model::class;

    public static array $schema = [
        'name' => [
            'string',
            'info' => 'The property name',
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
