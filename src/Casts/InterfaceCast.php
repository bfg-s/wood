<?php

namespace Bfg\Wood\Casts;

use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ModelTopic;
use ErrorException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class InterfaceCast implements CastsAttributes
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
     * @return InterfaceSubject
     */
    public function get($model, string $key, $value, array $attributes): InterfaceSubject
    {
        $key = $model::class . '-' . $model->id . '-' . $key;

        if (isset(InterfaceCast::$_cache[$key])) {
            return InterfaceCast::$_cache[$key];
        }

        return InterfaceCast::$_cache[$key] = app(ClassFactory::class)
            ->interface($value, $model);
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
        if ($value instanceof InterfaceSubject) {

            return $value->class;
        }

        return $value;
    }
}
