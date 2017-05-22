<?php

namespace Swaggest\JsonSchema;


use PhpLang\ScopeExit;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Constraint\UniqueItems;
use Swaggest\JsonSchema\Exception\ArrayException;
use Swaggest\JsonSchema\Exception\EnumException;
use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Exception\NumericException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Structure\ClassStructure;
use Swaggest\JsonSchema\Structure\Egg;
use Swaggest\JsonSchema\Structure\ObjectItem;

/**
 * Class Schema
 * @package Swaggest\JsonSchema
 */
class Schema extends ObjectItem
{
    const SCHEMA_DRAFT_04_URL = 'http://json-schema.org/draft-04/schema';

    /*
    public $__seqId;
    public static $seq = 0;

    public function __construct()
    {
        self::$seq++;
        $this->__seqId = self::$seq;
    }
    //*/

    /** @var Type */
    public $type;

    // Object
    /** @var Properties|Schema[] */
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
    /** @var string */
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
    /** @var string */
    public $format;

    const FORMAT_DATE_TIME = 'date-time'; // todo implement


    /** @var Schema[] */
    public $allOf;
    /** @var Schema */
    public $not;
    /** @var Schema[] */
    public $anyOf;
    /** @var Schema[] */
    public $oneOf;

    public $objectItemClass;
    private $useObjectAsArray = false;

    private $__dataToProperty = array();
    private $__propertyToData = array();


    public function addPropertyMapping($dataName, $propertyName)
    {
        $this->__dataToProperty[$dataName] = $propertyName;
        $this->__propertyToData[$propertyName] = $dataName;
        return $this;
    }

    private function preProcessReferences($data, ProcessingOptions $options = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $this->preProcessReferences($item, $options);
            }
        } elseif ($data instanceof \stdClass) {
            /** @var JsonSchema $data */
            if (isset($data->id) && is_string($data->id)) {
                $prev = $options->refResolver->setupResolutionScope($data->id, $data);
                /** @noinspection PhpUnusedLocalVariableInspection */
                $_ = new ScopeExit(function () use ($prev, $options) {
                    $options->refResolver->setResolutionScope($prev);
                });
            }

            foreach ((array)$data as $key => $value) {
                $this->preProcessReferences($value, $options);
            }
        }
    }

    public function import($data, ProcessingOptions $options = null)
    {
        if ($options === null) {
            $options = new ProcessingOptions();
        }

        $options->import = true;

        $options->refResolver = new RefResolver($data);
        if ($options->remoteRefProvider) {
            $options->refResolver->setRemoteRefProvider($options->remoteRefProvider);
        }

        if ($options->import) {
            $this->preProcessReferences($data, $options);
        }

        return $this->process($data, $options, '#');
    }

    public function export($data, ProcessingOptions $options = null)
    {
        if ($options === null) {
            $options = new ProcessingOptions();
        }

        $options->import = false;
        return $this->process($data, $options);
    }

    public function process($data, ProcessingOptions $options, $path = '#', $result = null)
    {

        $import = $options->import;
        //$pathTrace = explode('->', $path);

        if (!$import && $data instanceof ObjectItem) {
            $data = $data->jsonSerialize();
        }
        if (!$import && is_array($data) && $this->useObjectAsArray) {
            $data = (object)$data;
        }

        if (null !== $options->dataPreProcessor) {
            $data = $options->dataPreProcessor->process($data, $this, $import);
        }

        if ($result === null) {
            $result = $data;
        }

        if ($this->type !== null) {
            if (!Type::isValid($this->type, $data)) {
                $this->fail(new TypeException(ucfirst(
                        implode(', ', is_array($this->type) ? $this->type : array($this->type))
                        . ' expected, ' . json_encode($data) . ' received')
                ), $path);
            }
        }

        if ($this->enum !== null) {
            $enumOk = false;
            foreach ($this->enum as $item) {
                if ($item === $data) { // todo support complex structures here
                    $enumOk = true;
                    break;
                }
            }
            if (!$enumOk) {
                $this->fail(new EnumException('Enum failed'), $path);
            }
        }

        if ($this->not !== null) {
            $exception = false;
            try {
                $this->not->process($data, $options, $path . '->not');
            } catch (InvalidValue $exception) {
                // Expected exception
            }
            if ($exception === false) {
                $this->fail(new LogicException('Failed due to logical constraint: not'), $path);
            }
        }

        if ($this->oneOf !== null) {
            $successes = 0;
            $failures = '';
            foreach ($this->oneOf as $index => $item) {
                try {
                    $result = $item->process($data, $options, $path . '->oneOf:' . $index);
                    $successes++;
                    if ($successes > 1) {
                        break;
                    }
                } catch (InvalidValue $exception) {
                    $failures .= ' ' . $index . ': ' . $exception->getMessage() . "\n";
                    // Expected exception
                }
            }
            if ($successes === 0) {
                $this->fail(new LogicException('Failed due to logical constraint: no valid results for oneOf {' . "\n" . substr($failures, 0, -1) . "\n}"), $path);
            } elseif ($successes > 1) {
                $this->fail(new LogicException('Failed due to logical constraint: '
                    . $successes . '/' . count($this->oneOf) . ' valid results for oneOf'), $path);
            }
        }

        if ($this->anyOf !== null) {
            $successes = 0;
            $failures = '';
            foreach ($this->anyOf as $index => $item) {
                try {
                    $result = $item->process($data, $options, $path . '->anyOf:' . $index);
                    $successes++;
                    if ($successes) {
                        break;
                    }
                } catch (InvalidValue $exception) {
                    $failures .= ' ' . $index . ': ' . $exception->getMessage() . "\n";
                    // Expected exception
                }
            }
            if (!$successes) {
                $this->fail(new LogicException('Failed due to logical constraint: no valid results for anyOf {' . "\n" . substr($failures, 0, -1) . "\n}"), $path);
            }
        }

        if ($this->allOf !== null) {
            foreach ($this->allOf as $index => $item) {
                $result = $item->process($data, $options, $path . '->allOf' . $index);
            }
        }


        if (is_string($data)) {
            if ($this->minLength !== null) {
                if (mb_strlen($data, 'UTF-8') < $this->minLength) {
                    $this->fail(new StringException('String is too short', StringException::TOO_SHORT), $path);
                }
            }
            if ($this->maxLength !== null) {
                if (mb_strlen($data, 'UTF-8') > $this->maxLength) {
                    $this->fail(new StringException('String is too long', StringException::TOO_LONG), $path);
                }
            }
            if ($this->pattern !== null) {
                if (0 === preg_match(Helper::toPregPattern($this->pattern), $data)) {
                    $this->fail(new StringException(json_encode($data) . ' does not match to '
                        . $this->pattern, StringException::PATTERN_MISMATCH), $path);
                }
            }
        }

        if (is_int($data) || is_float($data)) {
            if ($this->multipleOf !== null) {
                $div = $data / $this->multipleOf;
                if ($div != (int)$div) {
                    $this->fail(new NumericException($data . ' is not multiple of ' . $this->multipleOf, NumericException::MULTIPLE_OF), $path);
                }
            }

            if ($this->maximum !== null) {
                if ($this->exclusiveMaximum === true) {
                    if ($data >= $this->maximum) {
                        $this->fail(new NumericException(
                            'Value less or equal than ' . $this->minimum . ' expected, ' . $data . ' received',
                            NumericException::MAXIMUM), $path);
                    }
                } else {
                    if ($data > $this->maximum) {
                        $this->fail(new NumericException(
                            'Value less than ' . $this->minimum . ' expected, ' . $data . ' received',
                            NumericException::MAXIMUM), $path);
                    }
                }
            }

            if ($this->minimum !== null) {
                if ($this->exclusiveMinimum === true) {
                    if ($data <= $this->minimum) {
                        $this->fail(new NumericException(
                            'Value more or equal than ' . $this->minimum . ' expected, ' . $data . ' received',
                            NumericException::MINIMUM), $path);
                    }
                } else {
                    if ($data < $this->minimum) {
                        $this->fail(new NumericException(
                            'Value more than ' . $this->minimum . ' expected, ' . $data . ' received',
                            NumericException::MINIMUM), $path);
                    }
                }
            }
        }


        if ($data instanceof \stdClass) {
            if ($this->required !== null) {
                foreach ($this->required as $item) {
                    if (!property_exists($data, $item)) {
                        $this->fail(new ObjectException('Required property missing: ' . $item, ObjectException::REQUIRED), $path);
                    }
                }
            }

            if ($import) {
                if ($this->useObjectAsArray) {
                    $result = array();
                } elseif (!$result instanceof ObjectItem) {
                    $result = $this->makeObjectItem();

                    if ($result instanceof ClassStructure) {
                        if ($result->__validateOnSet) {
                            $result->__validateOnSet = false;
                            /** @noinspection PhpUnusedLocalVariableInspection */
                            $validateOnSetHandler = new ScopeExit(function () use ($result) {
                                $result->__validateOnSet = true;
                            });
                        }
                    }

                    if ($result instanceof ObjectItem) {
                        $result->__documentPath = $path;
                    }
                }
            }

            if ($import) {
                try {
                    while (
                        isset($data->{'$ref'})
                        && is_string($data->{'$ref'})
                        && !isset($this->properties['$ref'])
                    ) {
                        $refString = $data->{'$ref'};
                        $preRefScope = $options->refResolver->getResolutionScope();
                        /** @noinspection PhpUnusedLocalVariableInspection */
                        $deferRefScope = new ScopeExit(function () use ($preRefScope, $options) {
                            $options->refResolver->setResolutionScope($preRefScope);
                        });
                        $ref = $options->refResolver->resolveReference($refString);
                        if ($ref->isImported()) {
                            $refResult = $ref->getImported();
                            return $refResult;
                        }
                        $data = $ref->getData();
                        if ($result instanceof ObjectItem) {
                            $result->__fromRef = $refString;
                        }
                        $ref->setImported($result);
                        $refResult = $this->process($data, $options, $path . '->ref:' . $refString, $result);
                        $ref->setImported($refResult);
                        return $refResult;
                    }
                } catch (InvalidValue $exception) {
                    $this->fail($exception, $path);
                }
            }

            // @todo better check for schema id

            if ($import && isset($data->id) && is_string($data->id) /*&& (!isset($this->properties['id']))/* && $this->isMetaSchema($data)*/) {
                $id = $data->id;
                $refResolver = $options->refResolver;
                $parentScope = $refResolver->updateResolutionScope($id);
                /** @noinspection PhpUnusedLocalVariableInspection */
                $defer = new ScopeExit(function () use ($parentScope, $refResolver) {
                    $refResolver->setResolutionScope($parentScope);
                });
            }

            if ($this->properties !== null) {
                /** @var Schema[] $properties */
                $properties = &$this->properties->toArray(); // TODO check performance of pointer
                if ($this->properties instanceof Properties) {
                    $nestedProperties = $this->properties->getNestedProperties();
                } else {
                    $nestedProperties = array();
                }
            }

            $array = array();
            if (!empty($this->__dataToProperty)) {
                foreach ((array)$data as $key => $value) {
                    if ($import) {
                        if (isset($this->__dataToProperty[$key])) {
                            $key = $this->__dataToProperty[$key];
                        }
                    } else {
                        if (isset($this->__propertyToData[$key])) {
                            $key = $this->__propertyToData[$key];
                        }
                    }
                    $array[$key] = $value;
                }
            } else {
                $array = (array)$data;
            }

            if ($this->minProperties !== null && count($array) < $this->minProperties) {
                $this->fail(new ObjectException("Not enough properties", ObjectException::TOO_FEW), $path);
            }
            if ($this->maxProperties !== null && count($array) > $this->maxProperties) {
                $this->fail(new ObjectException("Too many properties", ObjectException::TOO_MANY), $path);
            }
            foreach ($array as $key => $value) {
                if ($key === '' && PHP_VERSION_ID < 71000) {
                    $this->fail(new InvalidValue('Empty property name'), $path);
                }

                $found = false;
                if (isset($this->dependencies[$key])) {
                    $dependencies = $this->dependencies[$key];
                    if ($dependencies instanceof Schema) {
                        $dependencies->process($data, $options, $path . '->dependencies:' . $key);
                    } else {
                        foreach ($dependencies as $item) {
                            if (!property_exists($data, $item)) {
                                $this->fail(new ObjectException('Dependency property missing: ' . $item,
                                    ObjectException::DEPENDENCY_MISSING), $path);
                            }
                        }
                    }
                }

                $propertyFound = false;
                if (isset($properties[$key])) {
                    $prop = $properties[$key];
                    $propertyFound = true;
                    $found = true;
                    $value = $prop->process($value, $options, $path . '->properties:' . $key);
                }

                /** @var Egg[] $nestedEggs */
                $nestedEggs = null;
                if (isset($nestedProperties[$key])) {
                    $found = true;
                    $nestedEggs = $nestedProperties[$key];
                    // todo iterate all nested props?
                    $value = $nestedEggs[0]->propertySchema->process($value, $options, $path . '->nestedProperties:' . $key);
                }

                if ($this->patternProperties !== null) {
                    foreach ($this->patternProperties as $pattern => $propertySchema) {
                        if (preg_match(Helper::toPregPattern($pattern), $key)) {
                            $found = true;
                            $value = $propertySchema->process($value, $options,
                                $path . '->patternProperties[' . $pattern . ']:' . $key);
                            if ($import) {
                                $result->addPatternPropertyName($pattern, $key);
                            }
                            //break; // todo manage multiple import data properly (pattern accessor)
                        }
                    }
                }
                if (!$found && $this->additionalProperties !== null) {
                    if ($this->additionalProperties === false) {
                        $this->fail(new ObjectException('Additional properties not allowed'), $path . ':' . $key);
                    }

                    $value = $this->additionalProperties->process($value, $options, $path . '->additionalProperties:' . $key);
                    if ($import && !$this->useObjectAsArray) {
                        $result->addAdditionalPropertyName($key);
                    }
                }

                if ($nestedEggs && $import) {
                    foreach ($nestedEggs as $nestedEgg) {
                        $result->setNestedProperty($key, $value, $nestedEgg);
                    }
                    if ($propertyFound) {
                        $result->$key = $value;
                    }
                } else {
                    if ($this->useObjectAsArray && $import) {
                        $result[$key] = $value;
                    } else {
                        if ($found || !$import) {
                            $result->$key = $value;
                        } elseif (!isset($result->$key)) {
                            $result->$key = $value;
                        }
                    }
                }
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

            if ($items !== null || $additionalItems !== null) {
                $itemsLen = is_array($items) ? count($items) : 0;
                $index = 0;
                foreach ($result as $key => $value) {
                    if ($index < $itemsLen) {
                        $result[$key] = $items[$index]->process($value, $options, $path . '->items:' . $index);
                    } else {
                        if ($additionalItems instanceof Schema) {
                            $result[$key] = $additionalItems->process($value, $options, $path . '->' . $pathItems
                                . '[' . $index . ']');
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

    /**
     * @param boolean $useObjectAsArray
     * @return Schema
     */
    public function setUseObjectAsArray($useObjectAsArray)
    {
        $this->useObjectAsArray = $useObjectAsArray;
        return $this;
    }

    /**
     * @param bool|Schema $additionalProperties
     * @return Schema
     */
    public function setAdditionalProperties($additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }

    /**
     * @param Schema|Schema[] $items
     * @return Schema
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }


    private function fail(InvalidValue $exception, $path)
    {
        if ($path !== '#') {
            $exception->addPath($path);
        }
        throw $exception;
    }

    public static function integer()
    {
        $schema = new static();
        $schema->type = Type::INTEGER;
        return $schema;
    }

    public static function number()
    {
        $schema = new static();
        $schema->type = Type::NUMBER;
        return $schema;
    }

    public static function string()
    {
        $schema = new static();
        $schema->type = Type::STRING;
        return $schema;
    }

    public static function boolean()
    {
        $schema = new static();
        $schema->type = Type::BOOLEAN;
        return $schema;
    }

    public static function object()
    {
        $schema = new static();
        $schema->type = Type::OBJECT;
        return $schema;
    }

    public static function arr()
    {
        $schema = new static();
        $schema->type = Type::ARR;
        return $schema;
    }

    public static function null()
    {
        $schema = new static();
        $schema->type = Type::NULL;
        return $schema;
    }


    public static function create()
    {
        $schema = new static();
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

    public function setProperty($name, Schema $schema)
    {
        if (null === $this->properties) {
            $this->properties = new Properties();
        }
        $this->properties->__set($name, $schema);
        return $this;
    }

    /** @var Meta[] */
    private $metaItems = array();

    public function meta(Meta $meta)
    {
        $this->metaItems[get_class($meta)] = $meta;
        return $this;
    }

    public function getMeta($className)
    {
        if (isset($this->metaItems[$className])) {
            return $this->metaItems[$className];
        }
        return null;
    }

    /**
     * @return ObjectItem
     */
    public function makeObjectItem()
    {
        if (null === $this->objectItemClass) {
            return new ObjectItem();
        } else {
            return new $this->objectItemClass;
        }
    }
}
