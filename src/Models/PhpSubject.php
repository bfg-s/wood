<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Nodes\ClassPropertyNode;
use Bfg\Comcode\Subjects\ClassSubject;
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

    static array $maxs = [
        'method' => 0,
        'property' => 0
    ];

    public static function createOrUpdateSubject(
        ClassSubject $subject,
        ClassMethodNode|ClassPropertyNode $node,
        string $type,
    ) {
        /** @var null|Php $php */
        $php = $subject->php;
        if ($php) {

            $subject = $php->subjects()
                ->where('name', $node->getName())
                ->first();

            if (! $subject) {

                if ($php->subjects()
                    ->where('type', 'method')->count()) {
                    $processed = $php->subjects()
                            ->where('type', 'method')
                            ->max('processed')+1;
                    if (! static::$maxs[$type]) {
                        static::$maxs[$type] = $processed;
                    }
                }

                $php->subjects()->create([
                    'name' => $node->getName(),
                    'type' => $type,
                    'processed' => static::$maxs[$type],
                ]);
            } else {
                $subject->increment('processed');
            }
        }
    }
}
