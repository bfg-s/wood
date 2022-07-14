<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

class Config extends ModelTopic
{
    public ?string $name = "Configs";

    public string $icon = "fas fa-cog";

    public ?string $description = "Project wood config";

    public static array $schema = [
        'name' => [],
        'value' => ['nullable' => true],
    ];

    public static function seeds(): array
    {
        return [
            [
                'name' => 'date',
                'value' => date('Y_m_d')
            ]
        ];
    }
}
