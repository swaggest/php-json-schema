<?php

namespace Swaggest\JsonSchema\Exception;

use Swaggest\JsonSchema\InvalidValue;

class Error
{
    /** @var string */
    public $error;
    /** @var string[] */
    public $schemaPointers;
    /** @var string */
    public $dataPointer;
    /** @var string */
    public $processingPath;
    /** @var Error[] */
    public $subErrors;
}