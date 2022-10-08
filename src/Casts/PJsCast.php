<?php

namespace Bfg\Wood\Casts;

use Bfg\Wood\ModelTopic;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PJsCast implements CastsAttributes
{
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
        $value = $value && preg_match('/^faker.*/', $value)
            ? 'this.' . $value
            : $value;

        return $value ? preg_replace(
            '/([A-z\d)])[.?]([A-z\d])/',
            '$1->$2',
            ! preg_match('/^[A-z\d]+\(/', $value)
                ? '$' . $value
                : $value
        ) : null;
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
        return $value;
    }
}
