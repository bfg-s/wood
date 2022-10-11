<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\Generators\ResourceGenerator;
use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\Resource
 *
 * @property int $id
 * @property ClassSubject $class
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resource extends ModelTopic
{
    /**
     * @var string|null
     */
    protected static ?string $generator = ResourceGenerator::class;

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-poll-h';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Resources';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The laravel resources';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'unique' => true,
            'prepend' => "App\\Http\\Resources\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Resource class name',
        ],
    ];
}
