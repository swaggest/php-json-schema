<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Schema;

/**
 * @todo think of anyOf, allOf, oneOf
 */
class Composition extends Schema
{
    /**
     * @param Schema... $schema
     */
    public function __construct()
    {
        $this->type = Type::OBJECT;
        $properties = new Properties();
        $this->properties = $properties;

        foreach (func_get_args() as $arg) {
            if ($arg instanceof ClassSchema) {
                $properties->__set($arg->objectItemClass, $arg->nested());
            }
        }
    }

}