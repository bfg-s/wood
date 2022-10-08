<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

/**
 * Bfg\Wood\Models\Topic
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $icon
 * @property string $table
 * @property string $topic
 * @property string|null $parent_topic
 * @property array $settings
 * @property array $schema
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereParentTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereSchema($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Topic extends ModelTopic
{
    protected $fillable = [
        'icon',
        'name',
        'description',
        'table',
        'topic',
        'parent_topic',
        'settings',
        'schema',
    ];

    protected $casts = [
        'icon' => 'string',
        'name' => 'string',
        'description' => 'string',
        'table' => 'string',
        'topic' => 'string',
        'parent_topic' => 'string',
        'settings' => 'array',
        'schema' => 'array',
    ];

    /**
     * @param  ModelTopic  $topic
     * @return void
     */
    public static function createOrUpdateTopic(
        ModelTopic $topic
    ): void {
        $model = static::query()->where('topic', $topic::class)
            ->first();

        $data = [
            'table' => $topic->getTable(),
            'topic' => $topic::class,
            'parent_topic' => $topic->parent,
            'name' => $topic->name ?: class_basename($topic),
            'description' => $topic->description,
            'icon' => $topic->icon,
            'schema' => $topic::$schema,
            'settings' => [],
        ];

        if (! $model) {

            $model = static::query()
                ->create($data);
        }

        $settings = $model->settings ?: [];

        foreach ($topic->settings as $key => $setting) {

            if (! array_key_exists($key, $settings)) {

                $settings[$key] = $setting;
            }
        }

        $model->update(array_merge($data, [
            'settings' => $settings,
            'schema' => $topic::$schema,
        ]));
    }
}
