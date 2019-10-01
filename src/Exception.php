<?php

namespace Swaggest\JsonSchema;


class Exception extends \Exception
{
    const PROPERTIES_REQUIRED = 1;
    const UNDEFINED_NESTED_NAME = 2;
    const DEEP_NESTING = 3;
    const RESOLVE_FAILED = 4;
}