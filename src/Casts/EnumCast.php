<?php

namespace Bfg\Wood\Casts;

use Bfg\Comcode\Subjects\EnumSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ModelTopic;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EnumCast implements CastsAttributes
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
     * @return EnumSubject
     * @throws Exception
     */
    public function get($model, string $key, $value, array $attributes): object
    {
        $key = $model::class . '-' . $model->id . '-' . $key;

        if (isset(EnumCast::$_cache[$key])) {
            return EnumCast::$_cache[$key];
        }

        return EnumCast::$_cache[$key] = app(ClassFactory::class)
            ->enum($value, $model);
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
        if ($value instanceof EnumSubject) {

            return $value->class;
        }

        return $value;
    }
}
