<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;

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
            'settings' => $settings
        ]));
    }
}
