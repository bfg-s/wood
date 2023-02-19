<?php

namespace Bfg\Wood\Casts;

use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ModelTopic;
use ErrorException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AnonymousClassCast implements CastsAttributes
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
     * @return AnonymousClassSubject
     */
    public function get($model, string $key, $value, array $attributes): AnonymousClassSubject
    {
        $key = $model::class . '-' . $model->id . '-' . $key;

        if (isset(AnonymousClassCast::$_cache[$key])) {
            return AnonymousClassCast::$_cache[$key];
        }

        return AnonymousClassCast::$_cache[$key] = app(ClassFactory::class)
            ->anonymousClass($value, $model);
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
        if ($value instanceof AnonymousClassSubject) {

            return $value->class;
        }

        return $value;
    }
}
