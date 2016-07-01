<?php

namespace Yaoi\Schema;


use Yaoi\Schema\ArrayFlavour\Items;
use Yaoi\Schema\ArrayFlavour\MinItems;
use Yaoi\Schema\Logic\AllOf;
use Yaoi\Schema\ObjectFlavour\AdditionalProperties;
use Yaoi\Schema\ObjectFlavour\Properties;

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

    private $parentName;

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
    
    public function setParentSchema(Schema $parentSchema, $parentName)
    {
        $this->parentSchema = $parentSchema;
        $this->parentName = $parentName;
    }

    public function getPath()
    {
        $path = array($this->parentName);
        $parent = &$this;
        while ($parent = &$parent->parentSchema) {
            $path[] = $parent->parentName;
        }
        return $path;
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
                case Type::getSchemaKey():
                    $constraint = new Type($constraintData, $this);
                    break;
                case Properties::getSchemaKey():
                    $constraint = new Properties($constraintData, $this);
                    break;
                case AdditionalProperties::getSchemaKey():
                    $constraint = new AdditionalProperties($constraintData, $this);
                    break;
                case Items::getSchemaKey():
                    $constraint = new Items($constraintData, $this);
                    break;
                case Ref::getSchemaKey():
                    $constraint = new Ref($constraintData, $this);
                    break;
                case AllOf::getSchemaKey():
                    $constraint = new AllOf($constraintData, $this);
                    break;
                case MinItems::getSchemaKey():
                    $constraint = new MinItems($constraintData);
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

    static public $debug = false;
    
    public function import($data)
    {
        if (self::$debug) {
            print_r($data);
        }
        foreach ($this->constraints as $constraint) {
            if ($constraint instanceof Transformer) {
                $data = $constraint->import($data);
                if (self::$debug) {
                    var_dump(get_class($constraint), $data);
                }
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

    public function getConstraints()
    {
        return $this->constraints;
    }
}