<?php

namespace Yaoi\Schema;


class Schema extends Base implements Transformer, Constraint
{
    /**
     * @var Constraint[]
     */
    public $constraints = array();
    private $parentSchema;

    private $schemaData;

    /**
     * @return array
     */
    public function getSchemaData()
    {
        return $this->schemaData;
    }

    public function __construct($schemaValue, Schema $rootSchema = null)
    {
        $this->schemaData = $schemaValue;
        $this->parentSchema = $rootSchema;
        foreach ($schemaValue as $constraintName => $constraintData) {
            $constraint = null;
            switch ($constraintName) {
                case Type::SCHEMA_NAME:
                    $constraint = new Type($constraintData, $this);
                    break;
                case Properties::SCHEMA_NAME:
                    $constraint = new Properties($constraintData, $this);
                    break;
                case AdditionalProperties::SCHEMA_NAME:
                    $constraint = new AdditionalProperties($constraintData, $this);
                    break;
                case Ref::SCHEMA_NAME:
                    $constraint = new Ref($constraintData, $this);
                    break;
            }
            if (null !== $constraint) {
                $this->constraints[get_class($constraint)] = $constraint;
            }
        }
    }


    public function import($data)
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof Transformer) {
                $data = $constraint->import($data);
            }
        }
        return $data;
    }


    /**
     * @param $className
     * @return null|Constraint
     */
    public function getConstraint($className)
    {
        if (isset($this->constraints[$className])) {
            return $this->constraints[$className];
        }
        return null;
    }
}