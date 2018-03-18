<?php

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Exception\Error;
use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Path\PointerUtil;

class InvalidValue extends Exception
{
    public $error;
    public $path;

    public function addPath($path)
    {
        if ($this->error === null) {
            $this->error = $this->message;
        }
        $this->path = $path;
        $this->message .= ' at ' . $path;
    }

    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;


    public static function inspect(InvalidValue $invalidValue)
    {
        $error = new Error();
        $error->error = $invalidValue->error;
        $error->processingPath = $invalidValue->path;
        $error->dataPointer = PointerUtil::getDataPointer($error->processingPath);
        $error->schemaPointers = PointerUtil::getSchemaPointers($error->processingPath);
        if ($invalidValue instanceof LogicException) {
            foreach ($invalidValue->subErrors as $nestedError) {
                $error->subErrors[] = self::inspect($nestedError);
            }
        }
        return $error;
    }
}