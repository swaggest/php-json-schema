<?php

namespace Swaggest\JsonSchema\Exception;

use Swaggest\JsonSchema\InvalidValue;

class LogicException extends InvalidValue
{
    /** @var InvalidValue[] */
    public $subErrors;
}