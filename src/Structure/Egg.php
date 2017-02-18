<?php

namespace Swaggest\JsonSchema\Structure;


use Swaggest\JsonSchema\Schema;

class Egg
{


    /** @var Schema */
    public $classSchema;
    public $name;
    /** @var Schema */
    public $propertySchema;

    /**
     * Egg constructor.
     * @param Schema $classSchema
     * @param $name
     * @param Schema $propertySchema
     */
    public function __construct(Schema $classSchema, $name, Schema $propertySchema)
    {
        $this->classSchema = $classSchema;
        $this->name = $name;
        $this->propertySchema = $propertySchema;
    }
}