<?php

namespace Bfg\Wood;

use Bfg\Wood\Exceptions\InvalidValueByRegexp;
use Bfg\Wood\Exceptions\ParseHasPossibleVariants;
use Bfg\Wood\Exceptions\UndefinedDataForParameter;
use Illuminate\Support\Str;

class ArrayFactory
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @return $this
     * @throws InvalidValueByRegexp
     * @throws ParseHasPossibleVariants
     * @throws UndefinedDataForParameter
     */
    public function parse(): static
    {
        $file = database_path('wood.json');
        if (is_file($file)) {
            $this->data = json_decode(file_get_contents($file), 1);
        }

        foreach (\Wood::getTopics() as $topic) {
            /** @var ModelTopic $topic */
            $topic = new $topic;
            $part = $topic->getTable();
            if (isset($this->data[$part])) {
                foreach ($this->data[$part] as $key => $datum) {

                    $this->data[$part][$key] = $this->applyParseTopik(
                        $datum, $topic
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function compare(): static
    {
        foreach (\Wood::getTopics() as $topic) {
            /** @var ModelTopic $topic */
            $topic = new $topic;
            $part = $topic->getTable();
            if (isset($this->data[$part])) {
                $ids = collect($this->data[$part])->pluck('id')->filter()->toArray();
                $topic->query()->whereNotIn('id', $ids)->delete();
                foreach ($this->data[$part] as $key => $datum) {

                    $this->data[$part][$key] = $this->applyCompareTopik(
                        $datum, $topic, $topic
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function detect(): static
    {
        foreach (\Wood::getTopics() as $topic) {
            /** @var ModelTopic $topic */
            $topic = new $topic;
            $part = $topic->getTable();
            if (isset($this->data[$part])) {

                foreach ($this->data[$part] as $key => $datum) {

                    $this->data[$part][$key] = $this->applyDetectTopik(
                        $datum, $topic
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $file = database_path('wood.json');
        file_put_contents($file, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    /**
     * @param  array  $data
     * @param  ModelTopic  $topik
     * @param $topikForCreate
     * @param  int  $order
     * @return array
     */
    protected function applyCompareTopik(array $data, ModelTopic $topik, $topikForCreate, int $order = 0): array
    {
        $insertData = [];
        $currentTopik = null;

        foreach ($topik::$schema as $name => $item) {

            if (!isset($item['schema'])) {
                // Value is mod
                if (
                    isset($data[$name], $item['when_value_is'])
                    && is_array($item['when_value_is'])
                    && isset($item['when_value_is'][$data[$name]])
                    && is_array($item['when_value_is'][$data[$name]])
                ) {
                    foreach ($item['when_value_is'][$data[$name]] as $when_key => $when_value) {
                        if (! isset($insertData[$when_key], $data[$when_key])) {
                            $insertData[$when_key] = $when_value;
                        }
                    }
                }
                if (isset($data[$name])) {
                    $insertData[$name] = $data[$name];
                } else if (isset($item['default'])) {
                    $insertData[$name] = $insertData[$name] ?? $item['default'];
                } else if (isset($item['nullable']) && $item['nullable']) {
                    $insertData[$name] = null;
                }
            }
        }
        foreach ($topik::$schema as $name => $item) {

            // Pluralize mod
            if (
                !isset($item['schema'])
                && isset($item['pluralize'])
                && isset($insertData[$name])
            ) {
                foreach ($item['pluralize'] as $pluralize) {
                    if (
                        $insertData[$name] == $pluralize[0]
                        && isset($insertData[$pluralize[2]])
                    ) {
                        $insertData[$pluralize[2]] = call_user_func([Str::class, $pluralize[1]], $insertData[$pluralize[2]]);
                    }
                }
            }
        }

        $insertData['order'] = $order;

        if ($data['id']) {
            $currentTopik = $topikForCreate->where('id', $data['id'])->first();
        }

        $isCreated = false;

        if (! $currentTopik) {
            $firstField = array_keys($topik::$schema)[0];
            if (isset($data[$firstField])) {
                $currentTopik = $topikForCreate->where($firstField, $data[$firstField])->first();
            }
            if (! $currentTopik) {
                $currentTopik = $topikForCreate->create($insertData);
                $isCreated = true;
            }
        }
        if (!$isCreated && $currentTopik) {
            $currentTopik->update($insertData);
        }
        $data['id'] = $currentTopik->id;

        foreach ($topik::$schema as $name => $item) {

            if (isset($data[$name], $item['schema']) && $item['schema']) {
                if ($item['schema_type'] == 'HasMany') {
                    /** @var ModelTopic $topicChild */
                    $topicChild = new $item['schema'];
                    $ids = collect((array) $data[$name])->pluck('id')->filter()->toArray();
                    $currentTopik->{$name}()->whereNotIn('id', $ids)->delete();
                    foreach ((array) $data[$name] as $key => $datum) {
                        $data[$name][$key] = $this->applyCompareTopik(
                            $datum,
                            $topicChild,
                            $currentTopik->{$name}(),
                            $key
                        );
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param  array  $data
     * @param  ModelTopic  $topik
     * @return array
     * @throws ParseHasPossibleVariants
     * @throws InvalidValueByRegexp
     * @throws UndefinedDataForParameter
     */
    protected function applyParseTopik(array $data, ModelTopic $topik): array
    {
        $newData = [];

        $schema = $topik::$schema;

        $schema = ['id' => ['nullable' => true], ...$schema];

        foreach ($schema as $name => $item) {

            if (isset($data[$name])) {
                $newData[$name] = $data[$name];
            } else if ($name == 'id') {
                $newData[$name] = null;
            }
            if (
                isset($item['first_type'], $newData[$name])
                && ($item['first_type'] == 'class' || $item['first_type'] == 'trait' || $item['first_type'] == 'interface')
            ) {
                $newData[$name] = ucfirst(Str::camel($newData[$name]));
            }
            if (isset($newData[$name], $item['prepend']) && ! str_starts_with($newData[$name], $item['prepend']) && ! str_contains($newData[$name], '\\')) {
                $newData[$name] = $item['prepend'] . $newData[$name];
            }
            if (isset($newData[$name], $item['append']) && ! str_ends_with($newData[$name], $item['append']) && ! str_contains($newData[$name], '\\')) {
                $newData[$name] = $newData[$name] . $item['append'];
            }
            if (isset($newData[$name], $item['schema']) && $item['schema']) {
                if ($item['schema_type'] == 'HasMany') {

                    if (isset($item['default'])) {
                        $newData[$name] = $item['default'];
                    }
                    /** @var ModelTopic $topicChild */
                    $topicChild = new $item['schema'];
                    foreach ((array)$newData[$name] as $key => $datum) {

                        $newData[$name][$key] = $this->applyParseTopik(
                            $datum,
                            $topicChild
                        );
                    }
                } else {
                    if (isset($item['select'], $item['schema'], $newData[$name]) && $item['select']) {
                        $model = new $item['schema'];
                        $result = $model->where($item['select'], $newData[$name])->first();
                        if ($result) {
                            $newData[$name . '_id'] = $result->id;
                            $data[$name . '_id'] = $result->id;
                        }
                    }
                }
            } else {

                if (
                    ! isset($item['default'])
                    && ! isset($item['nullable'])
                    && isset($item['schema'])
                    && $item['schema']
                    && $item['schema_type'] == 'HasOne'
                ) {
                    if (! isset($data[$name . '_id'])) {
                        throw new UndefinedDataForParameter($name . '_id');
                    }
                }

                if (
                    ! isset($newData[$name])
                    && ! isset($item['default'])
                    && ! isset($item['nullable'])
                    && ! $item['schema']
                ) {
                    throw new UndefinedDataForParameter($name);
                }

                if (
                    isset($newData[$name], $item['regexp'])
                    && $item['regexp']
                ) {
                    if (! preg_match("/{$item['regexp']}/", $newData[$name])) {
                        throw new InvalidValueByRegexp($newData[$name], $item['regexp']);
                    }
                }
                if (
                    isset($newData[$name], $item['possible'])
                    && is_array($item['possible'])
                    && $item['possible']
                ) {
                    if (! in_array($newData[$name], $item['possible'])) {
                        throw new ParseHasPossibleVariants($newData[$name], $item['possible']);
                    }
                }
            }
        }

        return $newData;
    }

    /**
     * @param  array  $data
     * @param  ModelTopic  $topik
     * @return array
     */
    protected function applyDetectTopik(array $data, ModelTopic $topik): array
    {
        $newData = [];

        $schema = $topik::$schema;

        $schema = ['id' => ['nullable' => true], ...$schema];

        foreach ($schema as $name => $item) {

            if (isset($data[$name])) {
                $newData[$name] = $data[$name];
            }

            if (isset($item['schema']) && $item['schema']) {

                if ($item['schema_type'] == 'HasOne') {

                    if (isset($item['select'], $newData[$name]) && $item['select']) {
                        $model = new $item['schema'];
                        $result = $model->where($item['select'], $newData[$name])->first();
                        if ($result) {
                            $newData[$name . '_id'] = $result->id;
                            $data[$name . '_id'] = $result->id;
                        }
                    }
                }

                if ($item['schema_type'] == 'HasMany' && isset($newData[$name])) {

                    /** @var ModelTopic $topicChild */
                    $topicChild = new $item['schema'];
                    foreach ((array)$newData[$name] as $key => $datum) {

                        $newData[$name][$key] = $this->applyDetectTopik(
                            $datum,
                            $topicChild
                        );
                    }
                }
            }
        }

        return $newData;
    }
}
