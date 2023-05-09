<?php

namespace Bfg\Wood\Exceptions;

use Exception;

class InvalidValueByRegexp extends Exception
{
    public function __construct(string $value, string $regexp)
    {
        parent::__construct("Wrong value [$value] possible by regexp [$regexp]", 1);
    }
}
