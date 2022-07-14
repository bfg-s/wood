<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RequestRule extends ModelTopic
{
    public string $icon = 'fas fa-pencil-ruler';

    public ?string $name = 'Request rule';

    public ?string $description = 'The request rules';

    public ?string $parent = Request::class;

    /**
     * @var array
     */
    public static array $schema = [
        'name' => 'string',
        'rule' => ['string', 'nullable' => true],
        'class' => ['class', 'nullable' => true, 'unique' => true],
    ];
}
