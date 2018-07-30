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
        if ('#' !== $this->path) {
            $this->message .= ' at ' . $path;
        }
    }

    const INVALID_VALUE = 1;
    const NOT_IMPLEMENTED = 2;


    public function inspect()
    {
        $error = new Error();
        $error->error = $this->error;
        $error->processingPath = $this->path;
        $error->dataPointer = PointerUtil::getDataPointer($error->processingPath);
        $error->schemaPointers = PointerUtil::getSchemaPointers($error->processingPath);
        if ($this instanceof LogicException) {
            if ($this->subErrors !== null) {
                foreach ($this->subErrors as $subError) {
                    $error->subErrors[] = $subError->inspect();
                }
            }
        }
        return $error;
    }

    public function getSchemaPointer()
    {
        return PointerUtil::getSchemaPointer($this->path);
    }

    public function getDataPointer()
    {
        return PointerUtil::getDataPointer($this->path);
    }

}