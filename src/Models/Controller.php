<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\Generators\ControllerGenerator;
use Bfg\Wood\Generators\EventGenerator;
use Bfg\Wood\Generators\SeedGenerator;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\Seed
 *
 * @property int $id
 * @property ClassSubject $class
 * @property int $model_id
 * @property int $order
 * @property int $count
 * @property bool $factory
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Bfg\Wood\Models\Model|null $model
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\SeedRow[] $rows
 * @property-read int|null $rows_count
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seed whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Controller extends ModelTopic
{
    /**
     * @var array|string[]
     */
    protected static array $generators = [
        'general' => ControllerGenerator::class
    ];

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-gamepad';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Controllers';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The laravel controllers';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'info' => 'Controller class name',
            'unique' => true,
            'prepend' => "App\\Http\\Controllers\\",
            'append' => "Controller",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
        ],
        'methods' => [
            'info' => 'Event listen lines'
        ],
    ];

    /**
     * @return HasMany
     */
    public function methods(): HasMany
    {
        return $this->hasMany(ControllerMethod::class);
    }
}
