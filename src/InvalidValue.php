<?php

namespace Swaggest\JsonSchema;

use Swaggest\JsonDiff\JsonPointer;
use Swaggest\JsonSchema\Exception\Error;
use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Path\PointerUtil;
use Swaggest\JsonSchema\Structure\ObjectItemContract;

class InvalidValue extends Exception
{
    public $error;
    public $path;

    public $constraint;
    public $data;

    /**
     * @param mixed $constraint
     * @return $this
     */
    public function withConstraint($constraint)
    {
        $this->constraint = $constraint;
        return $this;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

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

    /**
     * @param Schema $schema
     * @return bool|Schema
     * @throws \Swaggest\JsonDiff\Exception
     */
    public function getFailedSubSchema(Schema $schema)
    {
        $schemaPointer = $this->getSchemaPointer();
        if ($schema instanceof ObjectItemContract) {
            $refs = $schema->getFromRefs();
            if ($refs !== null) {
                foreach ($refs as $ref) {
                    if (substr($schemaPointer, 0, strlen($ref)) === $ref) {
                        $schemaPointer = substr($schemaPointer, strlen($ref));
                    }
                }
            }
        }
        if (!(bool)$schemaPointer) {
            return $schema;
        }

        return JsonPointer::getByPointer($schema, $this->getSchemaPointer());
    }


    public function getDataPointer()
    {
        return PointerUtil::getDataPointer($this->path);
    }

}