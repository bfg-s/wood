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

    /**
     * @param  ClassSubject  $subject
     * @param  ClassMethodNode|ClassPropertyNode  $node
     * @param  string  $type
     * @return void
     */
    public static function createOrUpdateSubject(
        ClassSubject $subject,
        ClassMethodNode|ClassPropertyNode $node,
        string $type,
    ): void {
        /** @var null|Php $php */
        $php = $subject->php;
        if ($php) {

            $subject = $php->subjects()
                ->where('type', $type)
                ->where('name', $node->getName())
                ->first();

            if (! $subject) {

                $php->subjects()->create([
                    'name' => $node->getName(),
                    'type' => $type,
                    'processed' => $php->{"max_" . $type},
                ]);
            } else {
                $subject->update([
                    'processed' => $php->{"max_" . $type}
                ]);
            }
        }
    }
}
