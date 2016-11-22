<?php

namespace Yaoi\Schema\NG;


use Yaoi\Schema\Constraint\Definitions;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;

class SchemaLoader
{
    private $rootSchema;

    public function readSchema($schemaData)
    {
        $schema = new Schema();
        if (null === $this->rootSchema) {
            $this->rootSchema = $schema;
        }

        $definitionsKey = Definitions::getConstraintName();
        if (isset($schemaData[$definitionsKey])) {
            if (null === $schema->definitions) {
                $schema->definitions = new Definitions();
            }
            foreach ($schemaData[$definitionsKey] as $name => $defData) {
                $schema->definitions->__set($name, $this->readSchema($defData));
            }
        }

        if (isset($schemaData[Type::getConstraintName()])) {
            $schema->type = new Type($schemaData[Type::getConstraintName()]);
        }

        if (isset($schemaData[Properties::getConstraintName()])) {
            $schema->properties = new Properties();
            foreach ($schemaData[Properties::getConstraintName()] as $name => $data) {
                $schema->properties->__set($name, $this->readSchema($data));
            }
        }

        if (isset($schemaData[Ref::getConstraintName()])) {
            $schema->ref = new Ref($schemaData[Ref::getConstraintName()], $this->rootSchema);
        }

        return $schema;
    }


    public function writeSchema()
    {

    }

}