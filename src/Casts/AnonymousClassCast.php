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
     * Cast the given value.
     *
     * @param  ModelTopic  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return AnonymousClassSubject
     * @throws ErrorException
     */
    public function get($model, string $key, $value, array $attributes): AnonymousClassSubject
    {
        return app(ClassFactory::class)
            ->anonymousClass($value);
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
