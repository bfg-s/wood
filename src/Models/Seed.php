<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
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
class Seed extends ModelTopic
{
    /**
     * @var string|null
     */
    protected static ?string $generator = SeedGenerator::class;

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-seedling';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Seeders';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The laravel seeders';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'info' => 'Seed class name',
            'unique' => true,
            'prepend' => "Database\\Seeders\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
        ],
        'model' => [
            'select' => 'class',
            'info' => 'Model for the seed'
        ],
        'rows' => [
            'info' => 'Seeding data lines'
        ],
    ];

    /**
     * @return HasOne
     */
    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }

    /**
     * @return HasMany
     */
    public function rows(): HasMany
    {
        return $this->hasMany(SeedRow::class);
    }
}
