<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\Observer
 *
 * @property int $id
 * @property mixed|null $class
 * @property array $events
 * @property int $model_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Bfg\Wood\Models\Model|null $model
 * @method static \Illuminate\Database\Eloquent\Builder|Observer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Observer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Observer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Observer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Observer extends ModelTopic
{
    public string $icon = 'fas fa-eye';

    public ?string $name = 'Model observers';

    public ?string $description = 'The model observers';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'prepend' => "App\\Observers\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Observer class name',
        ],
        'model' => [
            'select' => 'class'
        ],
        'events' => [
            'array',
            'variants' => [
                'Retrieved' => ['retrieved'],
                'Creating' => ['creating'],
                'Created' => ['created'],
                'Updating' => ['updating'],
                'Updated' => ['updated'],
                'Saving' => ['saving'],
                'Saved' => ['saved'],
                'Restoring' => ['restoring'],
                'Restored' => ['restored'],
                'Replicating' => ['replicating'],
                'Deleting' => ['deleting'],
                'Deleted' => ['deleted'],
                'Force deleted' => ['forceDeleted'],
            ],
            'info' => 'Event list',
        ],
    ];

    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }
}
