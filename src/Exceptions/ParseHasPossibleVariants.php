<?php

namespace Bfg\Wood\Exceptions;

use Exception;

class ParseHasPossibleVariants extends Exception
{
    public function __construct(string $value, array $variants)
    {
        parent::__construct("Wrong value [$value] possible is " . json_encode($variants), 1);
    }
}
