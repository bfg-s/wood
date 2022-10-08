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
 * @property array|null $methods
 * @method static \Illuminate\Database\Eloquent\Builder|Php newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Php query()
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereInode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Php whereType($value)
 * @mixin \Eloquent
 */
class Php extends ModelTopic
{
    protected $table = 'php';

    protected $fillable = [
        'type',
        'file',
        'inode',
        'name',
        'methods',
    ];

    protected $casts = [
        'type' => 'string',
        'file' => 'string',
        'inode' => 'int',
        'name' => 'string',
        'methods' => 'array',
    ];

    public $timestamps = false;

    /**
     * @param  string  $class
     * @return void
     */
    public static function createOrUpdatePhp(
        string $class
    ): void {
        try {
            $ref = new ReflectionClass($class);
            if ($ref->getFileName()) {
                static::updateOrCreate([
                    'name' => $class,
                ], [
                    'type' => $ref->isInterface() ? 'interface' : ($ref->isTrait() ? 'trait' : 'class'),
                    'file' => str_replace(base_path(), '', $ref->getFileName()),
                    'inode' => fileinode($ref->getFileName()),
                    'methods' => get_class_methods($class)
                ]);
            }
        } catch (\Throwable $t) {
            return;
        }
    }
}
