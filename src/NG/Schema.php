<?php

namespace Yaoi\Schema\NG;


use Yaoi\Schema\Constraint\Definitions;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Structure\ObjectItem;

class Schema extends MagicMap
{
    /** @var StackTraceStorage */
    public static $traceHelper;

    /** @var Type */
    public $type;

    /** @var Properties */
    public $properties;

    /** @var Schema */
    public $additionalProperties;

    /** @var Schema */
    public $items;

    /** @var Definitions */
    public $definitions;

    /** @var Ref */
    public $ref;


    public function import($data)
    {
        $result = $data;
        if ($this->ref !== null) {
            $result = $this->ref->getSchema()->import($data);
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

        if ($this->properties !== null) {
            if ($data instanceof \stdClass || is_array($data)) {
                if (!$result instanceof ObjectItem) {
                    $result = new ObjectItem();
                }
                $this->properties->import($data, $result, $this);
            }

        } else {
            if ($data instanceof \stdClass || is_array($data)) {
                if ($this->additionalProperties
                    || ($this->type && $this->type->has(Type::OBJECT))
                ) {

                    if (!$result instanceof ObjectItem) {
                        $result = new ObjectItem();
                    }

                    foreach ((array)$data as $key => $value) {
                        if ($this->additionalProperties !== null) {
                            $value = $this->additionalProperties->import($value);
                        }
                        $result[$key] = $value;
                    }
                }
            }
        }

        if ($this->items) {
            if (is_array($data)) {
                foreach ($data as &$value) {
                    $value = $this->items->import($value);
                }
            }
        }


        return $result;
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
                throw new Exception('Invalid type', Exception::INVALID_VALUE);
            }
        }


        if ($this->properties !== null && ($data instanceof ObjectItem)) {
            $result = $this->properties->export($data);
        }

        return $result;
    }


    /**
     * @param $ref
     * @return $this|mixed
     * @throws \Exception
     */
    public function getDefinition($ref)
    {
        if ($ref === '#') {
            return $this;
        }

        if (substr($ref, 0, 14) === '#/definitions/') {
            $defName = substr($ref, 14);
            if (isset($this->definitions[$defName])) {
                return $this->definitions[$defName];
            }
        }

        throw new \Exception('Could not resolve ' . $ref);
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