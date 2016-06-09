<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Logic\AllOf;

/**
 * @method static Schema create($schemaValue = null, Schema $parentSchema = null)
 */
class Schema extends Base implements Transformer
{
    /**
     * @var Constraint[]
     */
    public $constraints = array();

    /**
     * @var null|Schema
     */
    private $parentSchema;

    private $schemaData;

    /**
     * @return null|Schema
     */
    public function getRootSchema()
    {
        $rootSchema = $this;
        while ($rootSchema->parentSchema && $rootSchema->parentSchema !== $rootSchema) {
            $rootSchema = $rootSchema->parentSchema;
        }
        return $rootSchema;
    }

    public function getParentSchema()
    {
        return $this->parentSchema;
    }
    
    public function setParentSchema(Schema $parentSchema)
    {
        $this->parentSchema = $parentSchema;
    }


    /**
     * @return array
     */
    public function getSchemaData()
    {
        return $this->schemaData;
    }

    /**
     * Schema constructor.
     * @param array|Constraint $schemaValue
     * @param Schema|null $parentSchema
     * @throws Exception
     */
    public function __construct($schemaValue = null, Schema $parentSchema = null)
    {
        if (null === $schemaValue) {
            return;
        }

        if ($schemaValue instanceof Constraint) {
            $this->setConstraint($schemaValue);
            return;
        }

        $this->schemaData = $schemaValue;
        $this->parentSchema = $parentSchema ? $parentSchema : null;

        foreach ($schemaValue as $constraintName => $constraintData) {
            $constraint = null;
            switch ($constraintName) {
                case Type::KEY:
                    $constraint = Type::factory($constraintData, $this);
                    break;
                case Properties::KEY:
                    $constraint = new Properties($constraintData, $this);
                    break;
                case AdditionalProperties::KEY:
                    $constraint = new AdditionalProperties($constraintData, $this);
                    break;
                case Ref::KEY:
                    $constraint = new Ref($constraintData, $this);
                    break;
                case AllOf::KEY:
                    $constraint = new AllOf($constraintData, $this);
                    break;
            }
            if (null !== $constraint) {
                $this->setConstraint($constraint);
            }
        }
    }

    public function setConstraint(Constraint $constraint)
    {
        $this->constraints[get_class($constraint)] = $constraint;
        $constraint->setOwnerSchema($this);
        return $this;
    }


    public function import($data)
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof Transformer) {
                $data = $constraint->import($data);
            }
            elseif ($constraint instanceof Validator) {
                if (!$constraint->isValid($data)) {
                    throw new Exception('Validation failed', Exception::INVALID_VALUE);
                }
            }
        }
        return $data;
    }

    public function export($data)
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof Transformer) {
                $data = $constraint->export($data);
            }
            elseif ($constraint instanceof Validator) {
                if (!$constraint->isValid($data)) {
                    throw new Exception('Validation failed', Exception::INVALID_VALUE);
                }
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