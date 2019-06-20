<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;
use Swaggest\JsonSchema\Wrapper;

/**
 * @todo think of anyOf, allOf, oneOf
 */
class Composition extends Schema
{
    /**
     * @param SchemaContract... $schema
     * @throws \Swaggest\JsonSchema\Exception
     */
    public function __construct()
    {
        $this->type = Type::OBJECT;
        $properties = new Properties();
        $this->properties = $properties;

        foreach (func_get_args() as $arg) {
            if ($arg instanceof Wrapper) {
                $properties->__set($arg->objectItemClass, $arg->nested());
            }
        }
    }

}