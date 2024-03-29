<?php

namespace Bfg\Wood\Casts;

use Bfg\Comcode\Subjects\TraitSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ModelTopic;
use ErrorException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TraitCast implements CastsAttributes
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
     * @return TraitSubject
     */
    public function get($model, string $key, $value, array $attributes): object
    {
        if (str_starts_with($value, 'Illuminate')) {
            return (object)['class' => $value];
        }

        $key = $model::class . '-' . $model->id . '-' . $key;

        if (isset(TraitCast::$_cache[$key])) {
            return TraitCast::$_cache[$key];
        }

        return TraitCast::$_cache[$key] = app(ClassFactory::class)
            ->trait($value, $model);
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
        if ($value instanceof TraitSubject) {

            return $value->class;
        }

        return $value;
    }
}
