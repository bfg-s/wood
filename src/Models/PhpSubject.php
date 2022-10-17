<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use ReflectionClass;
use ReflectionException;

/**
 * Bfg\Wood\Models\Php
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property int $processed
 * @property int $php_id
 * @method static \Illuminate\Database\Eloquent\Builder|Php newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php query()
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereType($value)
 * @mixin \Eloquent
 */
class PhpSubject extends ModelTopic
{
    /**
     * @var string
     */
    protected $table = 'php_subjects';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'processed',
        'php_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'type' => 'string',
        'processed' => 'int',
        'name' => 'string',
        'php_id' => 'int',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
