<?php

namespace Bfg\Wood\Casts;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ModelTopic;
use ErrorException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ClassCast implements CastsAttributes
{
    /**
     * @var array
     */
    protected static array $_cache = [];

    /**
     * Cast the given value.
     *
     * @param  ModelTopic  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return ClassSubject
     */
    public function get($model, string $key, $value, array $attributes): ClassSubject
    {
        $key = $model::class . '-' . $model->id . '-' . $key;

        if (isset(ClassCast::$_cache[$key])) {
            return ClassCast::$_cache[$key];
        }

        return ClassCast::$_cache[$key] = app(ClassFactory::class)
            ->class($value, $model);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  ModelTopic  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        if ($value instanceof ClassSubject) {

            return $value->class;
        }

        return $value;
    }
}
