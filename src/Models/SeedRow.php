<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\SeedRow
 *
 * @property int $id
 * @property array $row
 * @property int $order
 * @property int $seed_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereSeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeedRow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeedRow extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-grip-lines';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Seed rows';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The rows of seeder';

    /**
     * @var string|null
     */
    public ?string $parent = Seed::class;

    /**
     * @var array
     */
    public static array $schema = [
        'row' => [
            'array',
            'possibleTable' => 'model_fields:name',
            'info' => 'Rows of seed',
        ],
    ];
}
