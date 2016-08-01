<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Constraint\Items;
use Yaoi\Schema\Constraint\MinItems;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Constraint\AllOf;
use Yaoi\Schema\Constraint\Minimum;
use Yaoi\Schema\Constraint\AdditionalProperties;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Format;
use Yaoi\Schema\Constraint\MaxLength;
use Yaoi\Schema\Constraint\MinLength;

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
            if (isset(self::$constraintKeys[$constraintName])) {
                /** @var Constraint $class */
                $class = self::$constraintKeys[$constraintName];
                $constraint = new $class($constraintData, $this);
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

    public function import($data, $deepValidation = false)
    {
        if (self::$debug) {
            print_r($data);
        }
        $entity = $data;
        foreach ($this->constraints as $constraint) {
            $failReason = $constraint->importFailed($data, $entity);
            if ($failReason && !$deepValidation) {
                throw new Exception($failReason, Exception::INVALID_VALUE);
            }
            if (self::$debug) {
                var_dump(get_class($constraint), $data);
            }
        }
        return $entity;
    }

    public function export($entity, $deepValidation = false)
    {
        if (self::$debug) {
            print_r($entity);
        }
        $data = $entity;
        foreach ($this->constraints as $constraint) {
            $failReason = $constraint->exportFailed($entity, $data);
            if ($failReason && !$deepValidation) {
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
            Type::getSchemaKey() => Type::className(),
            Properties::getSchemaKey() => Properties::className(),
            /*
            AdditionalProperties::getSchemaKey() => AdditionalProperties::className(),
            Items::getSchemaKey() => Items::className(),
            Ref::getSchemaKey() => Ref::className(),
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

Schema::initConstraintKeys();