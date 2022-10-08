<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelTrait
 *
 * @property int $id
 * @property mixed|null $class
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelTrait whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelTrait extends ModelTopic
{
    public string $icon = 'fas fa-file-contract';

    public ?string $name = 'Model traits';

    public ?string $description = 'The model traits';

    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'trait',
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'The model trait name',
        ],
    ];
}
