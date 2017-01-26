<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Constraint\UniqueItems;
use Yaoi\Schema\Structure\ObjectItem;

class Schema extends MagicMap
{
    /** @var StackTraceStorage */
    public static $traceHelper;

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

    // Array
    /** @var Schema|Schema[] */
    public $items;
    /** @var Schema|bool */
    public $additionalItems;
    /** @var bool */
    public $uniqueItems;

    // Reference
    /** @var Ref */
    public $ref;

    // Enum
    /** @var array */
    public $enum;

    // Number
    public $maximum;
    public $exclusiveMaximum;
    public $minimum;
    public $exclusiveMinimum;


    // String
    public $pattern;

    public function import($data)
    {
        $result = $data;
        if ($this->ref !== null) {
            $result = $this->ref->getSchema()->import($data);
        }

        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                $this->fail(ucfirst(implode(', ', $this->type->types) . ' required'));
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
                $this->fail('Enum failed');
            }
        }

        if (is_string($data)) {
            if ($this->pattern) {
                if (0 === preg_match($this->pattern, $data)) {
                    $this->fail('Does not match to ' . $this->pattern);
                }
            }
        }

        if (is_int($data) || is_float($data)) {
            if ($this->maximum !== null) {
                if ($this->exclusiveMaximum === true) {
                    if ($data >= $this->maximum) {
                        $this->fail('Maximum value exceeded');
                    }
                } else {
                    if ($data > $this->maximum) {
                        $this->fail('Maximum value exceeded');
                    }
                }
            }

            if ($this->minimum !== null) {
                if ($this->exclusiveMinimum === true) {
                    if ($data <= $this->minimum) {
                        $this->fail('Minimum value exceeded');
                    }
                } else {
                    if ($data < $this->minimum) {
                        $this->fail('Minimum value exceeded');
                    }
                }
            }


        }

        if ($data instanceof \stdClass) {
            if ($this->required !== null) {
                foreach ($this->required as $item) {
                    if (!property_exists($data, $item)) {
                        $this->fail('Required property missing: ' . $item);
                    }
                }
            }

            if (!$result instanceof ObjectItem) {
                $result = new ObjectItem();
            }

            if ($this->properties !== null) {
                /** @var Schema[] $properties */
                $properties = &$this->properties->toArray();
            }

            foreach ((array)$data as $key => $value) {
                $found = false;
                if (isset($this->dependencies[$key])) {
                    $dependencies = $this->dependencies[$key];
                    if ($dependencies instanceof Schema) {
                        $dependencies->import($data);
                    } else {
                        foreach ($dependencies as $item) {
                            if (!property_exists($data, $item)) {
                                $this->fail('Dependency property missing: ' . $item);
                            }
                        }
                    }
                }

                if (isset($properties[$key])) {
                    $found = true;
                    $value = $properties[$key]->import($value);
                }

                if (!$found && $this->patternProperties !== null) {
                    foreach ($this->patternProperties as $pattern => $propertySchema) {
                        if (preg_match($pattern, $key)) {
                            $found = true;
                            $value = $propertySchema->import($value);
                            //break; // todo manage multiple import data properly (pattern accessor)
                        }
                    }
                }
                if (!$found && $this->additionalProperties !== null) {
                    if ($this->additionalProperties === false) {
                        $this->fail('Additional properties not allowed');
                    }

                    $value = $this->additionalProperties->import($value);
                }
                $result[$key] = $value;
            }

        }

        if (is_array($data)) {

            if ($this->items instanceof Schema) {
                $items = array();
                $additionalItems = $this->items;
            } elseif ($this->items === null) { // items defaults to empty schema so everything is valid
                $items = array();
                $additionalItems = true;
            } else { // listed items
                $items = $this->items;
                $additionalItems = $this->additionalItems;
            }

            if ($items || $additionalItems !== null) {
                $itemsLen = is_array($items) ? count($items) : 0;
                $index = 0;
                foreach ($data as &$value) {
                    if ($index < $itemsLen) {
                        $value = $items[$index]->import($value);
                    } else {
                        if ($additionalItems instanceof Schema) {
                            $value = $additionalItems->import($value);
                        } elseif ($additionalItems === false) {
                            $this->fail('Unexpected array item');
                        }
                    }
                    ++$index;
                }
            }

            if ($this->uniqueItems) {
                if (!UniqueItems::isValid($data)) {
                    $this->fail('Array is not unique');
                }
            }
        }


        return $result;
    }


    private function fail($message)
    {
        if ($traceFrames = Schema::$traceHelper->getClean()) {
            throw new Exception($message . ' at ' . implode('->', $traceFrames), Exception::INVALID_VALUE);
        } else {
            throw new Exception($message, Exception::INVALID_VALUE);
        }
    }

    public function export($data)
    {
        $result = $data;
        if ($this->ref !== null) {
            $result = $this->ref->getSchema()->export($data);
        }

        if ($data instanceof ObjectItem) {
            $result = $data->toArray();
        }

        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                $message = ucfirst(implode(', ', $this->type->types) . ' required');
                if ($traceFrames = Schema::$traceHelper->getClean()) {
                    throw new Exception($message . ' at ' . implode('->', $traceFrames), Exception::INVALID_VALUE);
                } else {
                    throw new Exception($message, Exception::INVALID_VALUE);
                }
            }
        }

        if ($this->properties !== null && ($data instanceof ObjectItem)) {
            $result = $this->properties->export($data);
        }

        if ($this->additionalItems) {
            if (is_array($data)) {
                foreach ($data as &$value) {
                    $value = $this->additionalItems->export($value);
                }
            }
        }


        return $result;
    }

    public static function integer()
    {
        $schema = new Schema();
        $schema->type = new Type(Type::INTEGER);
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


}

Schema::$traceHelper = new StackTraceStorage();