<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\Request
 *
 * @property int $id
 * @property mixed|null $class
 * @property mixed|null $access
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bfg\Wood\Models\RequestRule[] $rules
 * @property-read int|null $rules_count
 * @method static \Illuminate\Database\Eloquent\Builder|Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Request newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Request query()
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Request whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Request extends ModelTopic
{
    public string $icon = 'fas fa-broadcast-tower';

    public ?string $name = 'Requests';

    public ?string $description = 'The laravel requests';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => [
            'class',
            'prepend' => "App\\Http\\Requests\\",
            'regexp' => '^([A-Z]\w*\\\\?)+(?<!\\\\)$',
            'info' => 'Request class name',
        ],
        'access' => [
            'pjs',
            'default' => 'true',
            'info' => 'Access method return',
        ],
        'rules' => [
            'info' => 'Request rule list',
        ],
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(RequestRule::class);
    }
}
