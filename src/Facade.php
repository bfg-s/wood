<?php

namespace Bfg\Wood;

use Illuminate\Support\Facades\Facade as FacadeIlluminate;

class Facade extends FacadeIlluminate
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return WoodCore::class;
    }
}
