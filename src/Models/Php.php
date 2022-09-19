<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use ReflectionClass;
use ReflectionException;

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
