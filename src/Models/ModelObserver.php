<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\Generators\ModelGenerator\ModelObserverGenerator;
use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\Observer
 *
 * @property int $id
 * @property ClassSubject $class
 * @property array $events
 * @property int $model_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Bfg\Wood\Models\Model|null $model
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelObserver whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModelObserver extends ModelTopic
{
    /**
     * @var array|string[]
     */
    protected static array $generators = [
        ModelObserverGenerator::class
    ];

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-eye';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Model observers';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model observers';

    /**
     * @var string|null
     */
    public ?string $parent = Model::class;

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'prepend' => "App\\Observers\\",
            'append' => "Observer",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Observer class name',
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
}
