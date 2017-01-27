<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Constraint\UniqueItems;
use Yaoi\Schema\Exception\ArrayException;
use Yaoi\Schema\Exception\EnumException;
use Yaoi\Schema\Exception\LogicException;
use Yaoi\Schema\Exception\NumericException;
use Yaoi\Schema\Exception\ObjectException;
use Yaoi\Schema\Exception\StringException;
use Yaoi\Schema\Exception\TypeException;
use Yaoi\Schema\Structure\ObjectItem;

class Schema extends MagicMap
{
    /** @var Type */
    public $type;

    // Object
    /** @var Properties */
    public $properties;
    /** @var Schema|bool */
    public $additionalProperties;
    /** @var Schema[] */
    public $patternProperties;
    /** @var string[] */
    public $required;
    /** @var string[][]|Schema[] */
    public $dependencies;
    /** @var int */
    public $minProperties;
    /** @var int */
    public $maxProperties;

    // Array
    /** @var Schema|Schema[] */
    public $items;
    /** @var Schema|bool */
    public $additionalItems;
    /** @var bool */
    public $uniqueItems;
    /** @var int */
    public $minItems;
    /** @var int */
    public $maxItems;

    // Reference
    /** @var Ref */
    public $ref;

    // Enum
    /** @var array */
    public $enum;

    // Number
    /** @var int */
    public $maximum;
    /** @var bool */
    public $exclusiveMaximum;
    /** @var int */
    public $minimum;
    /** @var bool */
    public $exclusiveMinimum;
    /** @var float|int */
    public $multipleOf;


    // String
    /** @var string */
    public $pattern;
    /** @var int */
    public $minLength;
    /** @var int */
    public $maxLength;


    /** @var Schema[] */
    public $allOf;
    /** @var Schema */
    public $not;
    /** @var Schema[] */
    public $anyOf;
    /** @var Schema[] */
    public $oneOf;

    public function import($data)
    {
        return $this->process($data, true);
    }

    public function export($data)
    {
        return $this->process($data, false);
    }

    private function process($data, $import = true, $path = '#')
    {
        if (!$import && $data instanceof ObjectItem) {
            $data = $data->jsonSerialize();
        }
        $result = $data;
        if ($this->ref !== null) {
            // https://github.com/json-schema-org/JSON-Schema-Test-Suite/pull/129
            return $this->ref->getSchema()->process($data, $import, $path . '->' . $this->ref->ref);
        }

        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                $this->fail(new TypeException(ucfirst(implode(', ', $this->type->types) . ' required')), $path);
            }
        }

        if ($this->enum !== null) {
            $enumOk = false;
            foreach ($this->enum as $item) {
                if ($item === $data) {
                    $enumOk = true;
                    break;
                }
            }
            if (!$enumOk) {
                throw new EnumException('Enum failed ' . $path);
            }
        }

        if ($this->not !== null) {
            $exception = false;
            try {
                $this->not->process($data, $import, $path . '->not');
            } catch (InvalidValue $exception) {
            }
            if ($exception === false) {
                throw new LogicException('Failed due to logical constraint: not ' . $path);
            }
        }

        if ($this->oneOf !== null) {
            $successes = 0;
            foreach ($this->oneOf as $index => $item) {
                try {
                    $result = $item->process($data, $import, $path . '->oneOf:' . $index);
                    $successes++;
                    if ($successes > 1) {
                        break;
                    }
                } catch (InvalidValue $exception) {
                }
            }
            if ($successes !== 1) {
                throw new LogicException('Failed due to logical constraint: oneOf ' . $path);
            }
        }

        if ($this->anyOf !== null) {
            $successes = 0;
            foreach ($this->anyOf as $index => $item) {
                try {
                    $result = $item->process($data, $import, $path . '->anyOf:' . $index);
                    $successes++;
                    if ($successes) {
                        break;
                    }
                } catch (InvalidValue $exception) {
                }
            }
            if (!$successes) {
                throw new LogicException('Failed due to logical constraint: anyOf ' . $path);
            }
        }

        if ($this->allOf !== null) {
            foreach ($this->allOf as $index => $item) {
                $result = $item->process($data, $import, $path . '->allOf' . $index);
            }
        }


        if (is_string($data)) {
            if ($this->minLength !== null) {
                if (mb_strlen($data) < $this->minLength) {
                    throw new StringException('String is too short ' . $path, StringException::TOO_SHORT);
                }
            }
            if ($this->maxLength !== null) {
                if (mb_strlen($data) > $this->maxLength) {
                    throw new StringException('String is too long ' . $path, StringException::TOO_LONG);
                }
            }
            if ($this->pattern !== null) {
                if (0 === preg_match($this->pattern, $data)) {
                    throw new StringException('Does not match to ' . $this->pattern . ' ' . $path,
                        StringException::PATTERN_MISMATCH);
                }
            }
        }

        if (is_int($data) || is_float($data)) {
            if ($this->multipleOf !== null) {
                $div = $data / $this->multipleOf;
                if ($div != (int)$div) {
                    throw new NumericException($data . ' is not multiple of ' . $this->multipleOf . ' ' . $path,
                        NumericException::MULTIPLE_OF);
                }
            }

            if ($this->maximum !== null) {
                if ($this->exclusiveMaximum === true) {
                    if ($data >= $this->maximum) {
                        throw new NumericException('Maximum value exceeded ' . $path, NumericException::MAXIMUM);
                    }
                } else {
                    if ($data > $this->maximum) {
                        throw new NumericException('Maximum value exceeded ' . $path, NumericException::MAXIMUM);
                    }
                }
            }

            if ($this->minimum !== null) {
                if ($this->exclusiveMinimum === true) {
                    if ($data <= $this->minimum) {
                        throw new NumericException('Minimum value exceeded ' . $path, NumericException::MINIMUM);
                    }
                } else {
                    if ($data < $this->minimum) {
                        throw new NumericException('Minimum value exceeded ' . $path, NumericException::MINIMUM);
                    }
                }
            }


        }

        if ($data instanceof \stdClass) {
            if ($this->required !== null) {
                foreach ($this->required as $item) {
                    if (!property_exists($data, $item)) {
                        throw new ObjectException('Required property missing: ' . $item . ' ' . $path,
                            ObjectException::REQUIRED);
                    }
                }
            }

            if ($import && !$result instanceof ObjectItem) {
                $result = new ObjectItem();
            }

            if ($this->properties !== null) {
                /** @var Schema[] $properties */
                $properties = &$this->properties->toArray();
            }

            $array = (array)$data;
            if ($this->minProperties !== null && count($array) < $this->minProperties) {
                throw new ObjectException("Not enough properties " . $path, ObjectException::TOO_FEW);
            }
            if ($this->maxProperties !== null && count($array) > $this->maxProperties) {
                throw new ObjectException("Too many properties " . $path, ObjectException::TOO_MANY);
            }
            foreach ($array as $key => $value) {
                $found = false;
                if (isset($this->dependencies[$key])) {
                    $dependencies = $this->dependencies[$key];
                    if ($dependencies instanceof Schema) {
                        $dependencies->process($data, $import, $path . '->dependencies:' . $key);
                    } else {
                        foreach ($dependencies as $item) {
                            if (!property_exists($data, $item)) {
                                throw new ObjectException('Dependency property missing: ' . $item . ' ' . $path,
                                    ObjectException::DEPENDENCY_MISSING);
                            }
                        }
                    }
                }

                if (isset($properties[$key])) {
                    $found = true;
                    $value = $properties[$key]->process($value, $import, $path . '->properties:' . $key);
                }

                if ($this->patternProperties !== null) {
                    foreach ($this->patternProperties as $pattern => $propertySchema) {
                        if (preg_match($pattern, $key)) {
                            $found = true;
                            $value = $propertySchema->process($value, $import, $path . '->patternProperties:' . $pattern);
                            //break; // todo manage multiple import data properly (pattern accessor)
                        }
                    }
                }
                if (!$found && $this->additionalProperties !== null) {
                    if ($this->additionalProperties === false) {
                        $this->fail(new ObjectException('Additional properties not allowed'), $path);
                    }

                    $value = $this->additionalProperties->process($value, $import, $path . '->additionalProperties');
                }
                $result->$key = $value;
            }

        }

        if (is_array($data)) {

            if ($this->minItems !== null && count($data) < $this->minItems) {
                $this->fail(new ArrayException("Not enough items in array"), $path);
            }

            if ($this->maxItems !== null && count($data) > $this->maxItems) {
                $this->fail(new ArrayException("Too many items in array"), $path);
            }

            $pathItems = 'items';
            if ($this->items instanceof Schema) {
                $items = array();
                $additionalItems = $this->items;
            } elseif ($this->items === null) { // items defaults to empty schema so everything is valid
                $items = array();
                $additionalItems = true;
            } else { // listed items
                $items = $this->items;
                $additionalItems = $this->additionalItems;
                $pathItems = 'additionalItems';
            }

            if ($items || $additionalItems !== null) {
                $itemsLen = is_array($items) ? count($items) : 0;
                $index = 0;
                foreach ($data as &$value) {
                    if ($index < $itemsLen) {
                        $value = $items[$index]->process($value, $import, $path . '->items:' . $index);
                    } else {
                        if ($additionalItems instanceof Schema) {
                            $value = $additionalItems->process($value, $import, $path . '->' . $pathItems);
                        } elseif ($additionalItems === false) {
                            $this->fail(new ArrayException('Unexpected array item'), $path);
                        }
                    }
                    ++$index;
                }
            }

            if ($this->uniqueItems) {
                if (!UniqueItems::isValid($data)) {
                    $this->fail(new ArrayException('Array is not unique'), $path);
                }
            }
        }


        return $result;
    }


    private function fail(InvalidValue $exception, $path)
    {
        $exception->addPath($path);
        throw $exception;
    }

    public static function integer()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::INTEGER);
        return $schema;
    }

    public static function number()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::NUMBER);
        return $schema;
    }

    public static function string()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::STRING);
        return $schema;
    }

    public static function object()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::OBJECT);
        return $schema;
    }

    public static function create()
    {
        $schema = new Schema();
        return $schema;
    }


    /**
     * @param Properties $properties
     * @return Schema
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param Type $type
     * @return Schema
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    public function setProperty($name, Schema $schema)
    {
        if (null === $this->properties) {
            $this->properties = new Properties();
        }
        $this->properties->__set($name, $schema);
        return $this;
    }

}
