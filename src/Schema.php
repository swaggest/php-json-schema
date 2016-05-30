<?php

namespace Yaoi\Schema;


class Schema extends Base implements Transformer, Constraint
{
    /**
     * @var Constraint[]
     */
    public $constraints = array();
    private $rootSchema;
    private $parentSchema;

    private $schemaData;

    /**
     * @return array
     */
    public function getSchemaData()
    {
        return $this->schemaData;
    }

    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        if ($schemaValue instanceof Constraint) {
            $this->constraints[get_class($schemaValue)] = $schemaValue;
        }

        $this->schemaData = $schemaValue;
        $this->rootSchema = $rootSchema ? $rootSchema : $this;
        $this->parentSchema = $parentSchema ? $parentSchema : null;

        foreach ($schemaValue as $constraintName => $constraintData) {
            $constraint = null;
            switch ($constraintName) {
                case Type::TYPE:
                    $constraint = new Type($constraintData, $this->rootSchema, $this);
                    break;
                case Properties::PROPERTIES:
                    $constraint = new Properties($constraintData, $this->rootSchema, $this);
                    break;
                case AdditionalProperties::ADDITIONAL_PROPERTIES:
                    $constraint = new AdditionalProperties($constraintData, $this->rootSchema, $this);
                    break;
                case Ref::REF:
                    $constraint = new Ref($constraintData, $this->rootSchema, $this);
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