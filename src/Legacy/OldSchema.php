<?php

namespace Yaoi\Schema;


use Yaoi\Schema\OldConstraint\Ref;
use Yaoi\Schema\OldConstraint\Type;
use Yaoi\Schema\OldConstraint\AllOf;
use Yaoi\Schema\OldConstraint\Minimum;
use Yaoi\Schema\OldConstraint\AdditionalProperties;
use Yaoi\Schema\OldConstraint\Properties;
use Yaoi\Schema\OldConstraint\Format;
use Yaoi\Schema\OldConstraint\MaxLength;
use Yaoi\Schema\OldConstraint\MinLength;

/**
 * @method static OldSchema create($schemaValue = null, OldSchema $parentSchema = null)
 * @property Type $type
 * @property Properties $properties
 */
class OldSchema extends Base implements Transformer
{
    /**
     * @var Constraint[]
     */
    public $constraints = array();

    /**
     * @var null|OldSchema
     */
    private $parentSchema;

    private $parentName;

    private $schemaData;

    /**
     * @return null|OldSchema
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

    public function setParentSchema(OldSchema $parentSchema, $parentName)
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
     * @param OldSchema|null $parentSchema
     * @throws Exception
     */
    public function __construct($schemaValue = null, OldSchema $parentSchema = null)
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

        /** @var Constraint $class */
        foreach (self::$constraintKeys as $constraintKey => $class) {
            if (array_key_exists($constraintKey, $schemaValue)) {
                $constraint = new $class($schemaValue[$constraintKey], $this);
                $this->setConstraint($constraint);
            }
        }
    }

    public function setConstraint(Constraint $constraint)
    {
        $this->constraints[$constraint::getSchemaKey()] = $constraint;
        $constraint->setOwnerSchema($this);
        return $this;
    }

    static public $debug = false;

    public function import($data)
    {
        if (self::$debug) {
            print_r($data);
        }
        $entity = $data;
        foreach ($this->constraints as $constraint) {
            $failReason = $constraint->importFailed($data, $entity);
            if ($failReason) {
                throw new Exception($failReason, Exception::INVALID_VALUE);
            }
            if (self::$debug) {
                var_dump(get_class($constraint), $data);
            }
        }
        return $entity;
    }

    public function export($entity)
    {
        if (self::$debug) {
            print_r($entity);
        }
        $data = $entity;
        foreach ($this->constraints as $constraint) {
            $failReason = $constraint->exportFailed($entity, $data);
            if ($failReason) {
                throw new Exception($failReason, Exception::INVALID_VALUE);
            }
            if (self::$debug) {
                var_dump(get_class($constraint), $entity);
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


    private static $constraintKeys;

    public static function initConstraintKeys()
    {
        self::$constraintKeys = array(
            Ref::getSchemaKey() => Ref::className(),
            Type::getSchemaKey() => Type::className(),
            Properties::getSchemaKey() => Properties::className(),
            /*
            AdditionalProperties::getSchemaKey() => AdditionalProperties::className(),
            Items::getSchemaKey() => Items::className(),
            AllOf::getSchemaKey() => AllOf::className(),
            MinItems::getSchemaKey() => MinItems::className(),
            Format::getSchemaKey() => Format::className(),
            Minimum::getSchemaKey() => Minimum::className(),
            MinLength::getSchemaKey() => MinLength::className(),
            MaxLength::getSchemaKey() => MaxLength::className(),
            */
        );

    }
}

OldSchema::initConstraintKeys();