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
 * @property string $file
 * @property int $inode
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Php newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php query()
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereInode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereType($value)
 * @mixin \Eloquent
 */
class Php extends ModelTopic
{
    /**
     * @var string
     */
    protected $table = 'php';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'file',
        'inode',
        'name',
        'methods',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'type' => 'string',
        'file' => 'string',
        'inode' => 'int',
        'name' => 'string',
        'methods' => 'array',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param  array  $data
     * @return void
     */
    public static function createOrUpdatePhp(
        array $data
    ): void {
        try {
            $ref = new ReflectionClass($data['class']);
            if ($ref->getFileName()) {
                static::updateOrCreate([
                    'inode' => $data['inode'],
                ], [
                    'type' => $ref->isInterface() ? 'interface' : ($ref->isTrait() ? 'trait' : 'class'),
                    'file' => str_replace(base_path(), '', $ref->getFileName()),
                    'name' => $data['class'],
                ]);
            }
        } catch (\Throwable $t) {
            return;
        }
    }
}
