<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\ModelImplement
 *
 * @property int $id
 * @property InterfaceSubject $class
 * @property int $order
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelImplement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelImplement extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-award';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model interfaces';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model interfaces';

    /**
     * @var string|null
     */
    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'interface',
            'prepend' => "App\\Interfaces\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Interface name for model',
        ],
    ];
}
