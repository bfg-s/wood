<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends ModelTopic
{
    public string $icon = 'fas fa-broadcast-tower';

    public ?string $name = 'Requests';

    public ?string $description = 'The laravel requests';

    /**
     * @var array
     */
    public static array $schema = [
        'class' => 'class',
        'access' => 'pjs',
        'rules' => [],
    ];

    public function rules(): HasOne
    {
        return $this->hasOne(RequestRule::class);
    }
}
