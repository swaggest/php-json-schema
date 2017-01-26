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
        $result = $data;
        if ($this->ref !== null) {
            // https://github.com/json-schema-org/JSON-Schema-Test-Suite/pull/129
            return $this->ref->getSchema()->import($data);
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

        if ($this->not !== null) {
            $exception = false;
            try {
                $this->not->import($data);
            } catch (Exception $exception) {
            }
            if ($exception === false) {
                $this->fail('Failed due to logical constraint: not');
            }
        }

        if ($this->oneOf !== null) {
            $successes = 0;
            foreach ($this->oneOf as $item) {
                try {
                    $result = $item->import($data);
                    $successes++;
                    if ($successes > 1) {
                        break;
                    }
                } catch (Exception $exception) {
                }
            }
            if ($successes !== 1) {
                $this->fail('Failed due to logical constraint: oneOf');
            }
        }

        if ($this->anyOf !== null) {
            $successes = 0;
            foreach ($this->anyOf as $item) {
                try {
                    $result = $item->import($data);
                    $successes++;
                    if ($successes) {
                        break;
                    }
                } catch (Exception $exception) {
                }
            }
            if (!$successes) {
                $this->fail('Failed due to logical constraint: anyOf');
            }
        }

        if ($this->allOf !== null) {
            foreach ($this->allOf as $item) {
                $result = $item->import($data);
            }
        }


        if (is_string($data)) {
            if ($this->minLength !== null) {
                if (mb_strlen($data) < $this->minLength) {
                    $this->fail('String is too short');
                }
            }
            if ($this->maxLength !== null) {
                if (mb_strlen($data) > $this->maxLength) {
                    $this->fail('String is too long');
                }
            }
            if ($this->pattern !== null) {
                if (0 === preg_match($this->pattern, $data)) {
                    $this->fail('Does not match to ' . $this->pattern);
                }
            }
        }

        if (is_int($data) || is_float($data)) {
            if ($this->multipleOf !== null) {
                $div = $data / $this->multipleOf;
                if ($div != (int)$div) {
                    $this->fail($data . ' is not multiple of ' . $this->multipleOf);
                }
            }

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

            $array = (array)$data;
            if ($this->minProperties !== null && count($array) < $this->minProperties) {
                $this->fail("Not enough properties");
            }
            if ($this->maxProperties !== null && count($array) > $this->maxProperties) {
                $this->fail("Too many properties");
            }
            foreach ($array as $key => $value) {
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

                if ($this->patternProperties !== null) {
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

            if ($this->minItems !== null && count($data) < $this->minItems) {
                $this->fail("Not enough items in array");
            }

            if ($this->maxItems !== null && count($data) > $this->maxItems) {
                $this->fail("Too many items in array");
            }

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
        throw new Exception('Implement me');

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