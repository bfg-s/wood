<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\Config
 *
 * @property int $id
 * @property string $name
 * @property string|null $value
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereValue($value)
 * @mixin \Eloquent
 */
class Config extends ModelTopic
{
    /**
     * @var string|null
     */
    public ?string $modelName = "Configs";

    /**
     * @var string
     */
    public string $modelIcon = "fas fa-cog";

    /**
     * @var string|null
     */
    public ?string $modelDescription = "Project wood config";

    /**
     * @var array
     */
    public static array $schema = [
        'name' => [
            'string',
            'regexp' => '^\w*$',
            'info' => 'The name of config',
        ],
        'value' => [
            'nullable' => true,
            'info' => 'The value of the config',
        ],
    ];

    /**
     * @return array[]
     */
    public static function seeds(): array
    {
        return [
            [
                'name' => 'migration_prepend',
                'value' => date('Y_m_d')
            ]
        ];
    }
}
