<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Comcode\Subjects\TraitSubject;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'processed',
        'topic_type',
        'topic_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'type' => 'string',
        'file' => 'string',
        'inode' => 'int',
        'name' => 'string',
        'processed' => 'int',
        'topic_type' => 'string',
        'topic_id' => 'int',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(PhpSubject::class, 'php_id', 'id');
    }

    /**
     * @param  ModelTopic  $modelTopic
     * @return Php|null
     */
    public static function findByTopic(ModelTopic $modelTopic): Php|null
    {
        return static::where('topic_type', get_class($modelTopic))
            ->where('topic_id', $modelTopic->id)->first();
    }

    /**
     * @param  ClassSubject  $subject
     * @return void
     */
    public static function createOrUpdatePhp(
        ClassSubject $subject
    ): void {
        try {

            $type = $subject instanceof InterfaceSubject ? 'interface'
                : ($subject instanceof TraitSubject ? 'trait' : 'class');

            $result = static::updateOrCreate([
                'inode' => fileinode($subject->fileSubject->file),
            ], [
                'type' => $type,
                'file' => str_replace(base_path(), '', $subject->fileSubject->file),
                'name' => $subject->class,
                'topic_id' => $subject->modelTopic->id,
                'topic_type' => get_class($subject->modelTopic),
            ]);

            $result->increment('processed');
        } catch (\Throwable $t) {
            return;
        }
    }
}
