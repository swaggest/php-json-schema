<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\Properties;
use Yaoi\Schema\AbstractStructure;


/**
 * @todo properly process directly accessed (with skipped accessors) properties
 */
abstract class ClassStructure extends AbstractStructure implements ClassStructureContract
{

    // TODO why empty final constructor?
    // Because object has two purposes Schema and Data, Data is created as plain object of properties.
    // Custom constructor may block `new Data`
    final public function __construct()
    {
    }

    /**
     * @return static|Properties
     * @todo do me and myself really need two classes (ClassStructure and Properties) for that?
     * @todo yes, because 
     * @see ClassStructure is 2-purpose entity, for Structure and for data instance
     */
    public static function properties()
    {
        static $propertiesStorage = array();

        $className = static::className();
        $properties = &$propertiesStorage[$className];
        if (null !== $properties) {
            return $properties;
        }
        $properties = new Properties($className);
        static::setUpDefinition($properties);
        return $properties;
    }

    private $data = array();

    public function __set($name, $value)
    {
        $property = static::properties()->getByName($name);
        if (null === $property) {
            throw new Exception('Setting unknown property ' . $name, Exception::UNKNOWN_PROPERTY);
        }
        try {
            $property->export($value); // validate value by trying an export
        } catch (Exception $exception) {
            throw $this->propagateException($name, $exception);
        }
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    private $directAccessProperties = null;

    /**
     * @param mixed $data
     * @return static
     * @throws Exception
     */
    protected function innerImport($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected', Exception::INVALID_VALUE);
        }

        $instance = new static;
        $properties = self::properties();
        $propertiesArray = $properties->getArray();

        if (null === $this->directAccessProperties) {
            $this->directAccessProperties = array();
            $directAccessProperties = (array)$instance;
            foreach ($propertiesArray as $propertyName => $property) {
                if (array_key_exists($propertyName, $directAccessProperties)) {
                    $this->directAccessProperties[$propertyName] = true;
                }
            }
        }


        foreach ($propertiesArray as $propertyName => $property) {
            $name = $property->getDataKeyName();
            $value = isset($data[$name]) ? $data[$name] : null;

            try {
                $value = $property->import($value);
            } catch (Exception $exception) {
                throw $this->propagateException($name, $exception);
            }

            if ($value !== null) {
                if (isset($this->directAccessProperties)) {
                    $instance->$propertyName = $value;
                } else {
                    $instance->data[$propertyName] = $value;
                }
            }
        }
        return $instance;
    }

    /**
     * @param static|ClassStructureContract $structure
     * @return array
     * @throws Exception
     */
    protected function innerExport($structure)
    {
        if (!$structure instanceof static) {
            throw new Exception('Object of ' . static::className() . ' expected', Exception::INVALID_VALUE);
        }

        $result = array();
        foreach (self::properties()->getArray() as $propertyName => $property) {
            $name = $property->getDataKeyName();
            if (isset($structure->data[$propertyName])) {
                $value = $structure->data[$propertyName];
            } elseif (isset($structure->$propertyName)) {
                $value = $structure->$propertyName;
            } else {
                $value = null;
            }

            try {
                $value = $property->export($value);
            } catch (Exception $exception) {
                throw $this->propagateException($name, $exception);
            }

            if (null !== $value) {
                $result[$name] = $value;
            }
        }
        return $result;
    }

}