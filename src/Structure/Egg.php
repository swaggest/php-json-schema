<?php

namespace Swaggest\JsonSchema\Structure;

use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;
use Swaggest\JsonSchema\Wrapper;

class Egg
{


    /** @var SchemaContract */
    public $classSchema;
    public $name;
    /** @var SchemaContract */
    public $propertySchema;

    /**
     * Egg constructor.
     * @param SchemaContract $classSchema
     * @param string $name
     * @param SchemaContract $propertySchema
     */
    public function __construct(SchemaContract $classSchema, $name, SchemaContract $propertySchema)
    {
        $this->classSchema = $classSchema;
        $this->name = $name;
        $this->propertySchema = $propertySchema;
    }
}