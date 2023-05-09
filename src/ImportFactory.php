<?php

namespace Bfg\Wood;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ImportFactory
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @return $this
     */
    public function parse(): static
    {
        foreach (\Wood::getTopics() as $topic) {
            /** @var ModelTopic $topic */
            $topic = new $topic;
            $part = $topic->getTable();

            $r = $this->getParseTopik(
                $topic, $topic
            );

            if ($r) {
                $this->data[$part] = $r;
            }
        }

        return $this;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        //$file = database_path('wood.json');
        //file_put_contents($file, json_encode($this->data, JSON_PRETTY_PRINT));
        dd($this->data);
    }

    protected function getParseTopik(ModelTopic $topik, ModelTopic|HasMany|HasOne $parentGet, $ignoreField = null): array
    {
        $schema = $topik::$schema;

        $all = $parentGet->get();

        $data = [];

        foreach ($all as $itemTopik) {

            $d = [
                'id' => $itemTopik->id
            ];

            foreach ($schema as $name => $item) {

                if (
                    $ignoreField == $name
                    || $name == 'order'
                ) {
                    continue;
                }

                if (isset($item['schema']) && $item['schema']) {
                    if ($item['schema_type'] == 'HasMany') {
                        /** @var ModelTopic $topicChild */
                        $topicChild = new $item['schema'];
                        /** @var HasOne|HasMany $relation */
                        $relation = $itemTopik->{$name}();
                        $r = $this->getParseTopik(
                            $topicChild, $relation, $relation->getForeignKeyName()
                        );
                        if ($r) {
                            $d[$name] = $r;
                        }
                    }
                } else {
                    $r = $itemTopik->getRawOriginal($name);

                    if ($item['cast'] == 'array') {
                        $r = json_decode($r);
                    }
                    if (isset($item['default'])) {
                        if ($item['default'] != $r) {
                            $d[$name] = $r;
                        }
                    } else if (isset($item['nullable']) && $item['nullable']) {
                        if ($r) {
                            $d[$name] = $r;
                        }
                    } else {
                        $d[$name] = $r;
                    }
                }
            }

            $data[] = $d;
        }

        return $data;
    }
}
