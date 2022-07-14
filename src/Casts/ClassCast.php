<?php

namespace Bfg\Wood\Casts;

use Bfg\Wood\ModelTopic;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ClassCast implements CastsAttributes
{
    /**
     * @var string
     */
    protected string $comCodeDriver = "class";

    /**
     * Cast the given value.
     *
     * @param  ModelTopic  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
//        if ($value) {
//
//            $value = json_decode($value, 1);
//
//
//        }

        return $value;
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
//        return json_encode([$value]);
        return $value;
    }
}
