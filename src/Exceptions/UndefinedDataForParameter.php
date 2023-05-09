<?php

namespace Bfg\Wood\Exceptions;

use Exception;

class UndefinedDataForParameter extends Exception
{
    public function __construct(string $parameter)
    {
        parent::__construct("Undefined value for parameter [$parameter]", 2);
    }
}
