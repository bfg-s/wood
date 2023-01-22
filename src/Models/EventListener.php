<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\Casts\ClassCast;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * Bfg\Wood\Models\SeedRow
 *
 * @property int $id
 * @property string $row
 * @property int $order
 * @property int $event_id
 * @property ClassSubject $class
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
class EventListener extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-grip-lines';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Event listeners';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The rows of event listeners';

    /**
     * @var string|null
     */
    public ?string $parent = Event::class;

    /**
     * @var array
     */
    public static array $schema = [
        'row' => [
            'string',
            'info' => 'Rows of event',
            'regexp' => '^\w*$',
            'possibleTable' => 'event_listeners:name',
        ],
    ];

    /**
     * @return HasOne
     */
    public function event(): HasOne
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function getClassAttribute()
    {
        /** @var Event $event */
        $event = $this->event()->first();

        $folderName = class_basename($event->class->class);

        $name = ucfirst(Str::camel($this->row));

        return (new ClassCast())->get(
            $this,
            'class',
            "App\\Listeners\\$folderName\\{$name}Listener",
            $this->attributes
        );
    }
}
