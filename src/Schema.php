<?php

namespace Swaggest\JsonSchema;


use PhpLang\ScopeExit;
use Swaggest\JsonDiff\JsonDiff;
use Swaggest\JsonDiff\JsonPointer;
use Swaggest\JsonSchema\Constraint\Content;
use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\Constraint\UniqueItems;
use Swaggest\JsonSchema\Exception\ArrayException;
use Swaggest\JsonSchema\Exception\ConstException;
use Swaggest\JsonSchema\Exception\EnumException;
use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\Exception\NumericException;
use Swaggest\JsonSchema\Exception\ObjectException;
use Swaggest\JsonSchema\Exception\StringException;
use Swaggest\JsonSchema\Exception\TypeException;
use Swaggest\JsonSchema\Meta\MetaHolder;
use Swaggest\JsonSchema\Path\PointerUtil;
use Swaggest\JsonSchema\Structure\ClassStructure;
use Swaggest\JsonSchema\Structure\Egg;
use Swaggest\JsonSchema\Structure\ObjectItem;
use Swaggest\JsonSchema\Structure\ObjectItemContract;
use Swaggest\JsonSchema\Structure\WithResolvedValue;

/**
 * Class Schema
 * @package Swaggest\JsonSchema
 */
class Schema extends JsonSchema implements MetaHolder, SchemaContract, HasDefault
{
    const ENUM_NAMES_PROPERTY = 'x-enum-names';
    const CONST_PROPERTY = 'const';
    const DEFAULT_PROPERTY = 'default';

    const DEFAULT_MAPPING = 'default';

    const VERSION_AUTO = 'a';
    const VERSION_DRAFT_04 = 4;
    const VERSION_DRAFT_06 = 6;
    const VERSION_DRAFT_07 = 7;

    const PROP_REF = '$ref';
    const PROP_ID = '$id';
    const PROP_ID_D4 = 'id';

    // Object
    /** @var null|Properties */
    public $properties;
    /** @var SchemaContract|bool */
    public $additionalProperties;
    /** @var SchemaContract[]|Properties */
    public $patternProperties;
    /** @var string[][]|Schema[]|\stdClass */
    public $dependencies;

    // Array
    /** @var null|SchemaContract|SchemaContract[] */
    public $items;
    /** @var null|SchemaContract|bool */
    public $additionalItems;

    /** @var SchemaContract[] */
    public $allOf;
    /** @var SchemaContract */
    public $not;
    /** @var SchemaContract[] */
    public $anyOf;
    /** @var SchemaContract[] */
    public $oneOf;

    /** @var SchemaContract */
    public $if;
    /** @var SchemaContract */
    public $then;
    /** @var SchemaContract */
    public $else;


    public $objectItemClass;

    /**
     * @todo check usages/deprecate
     * @var bool
     */
    private $useObjectAsArray = false;

    private $__booleanSchema;

    public function addPropertyMapping($dataName, $propertyName, $mapping = self::DEFAULT_MAPPING)
    {
        if (null === $this->properties) {
            $this->properties = new Properties();
        }

        $this->properties->addPropertyMapping($dataName, $propertyName, $mapping);
        return $this;
    }

    /**
     * @param mixed $data
     * @param Context|null $options
     * @return SchemaContract
     * @throws Exception
     * @throws InvalidValue
     * @throws \Exception
     */
    public static function import($data, Context $options = null)
    {
        if (null === $options) {
            $options = new Context();
        }

        $options->applyDefaults = false;

        if (isset($options->schemasCache) && is_object($data)) {
            if ($options->schemasCache->contains($data)) {
                return $options->schemasCache->offsetGet($data);
            } else {
                $schema = parent::import($data, $options);
                $options->schemasCache->attach($data, $schema);
                return $schema;
            }
        }

        // string $data is expected to be $ref uri
        if (is_string($data)) {
            $data = (object)array(self::PROP_REF => $data);
        }

        $data = self::unboolSchema($data);
        if ($data instanceof SchemaContract) {
            return $data;
        }

        return parent::import($data, $options);
    }

    /**
     * @param mixed $data
     * @param Context|null $options
     * @return array|mixed|null|object|\stdClass
     * @throws Exception
     * @throws InvalidValue
     * @throws \Exception
     */
    public function in($data, Context $options = null)
    {
        if (null !== $this->__booleanSchema) {
            if ($this->__booleanSchema) {
                return $data;
            } elseif (empty($options->skipValidation)) {
                $this->fail((new InvalidValue('Denied by false schema'))->withData($data), '#');
            }
        }

        if ($options === null) {
            $options = new Context();
        }

        $options->import = true;

        if ($options->refResolver === null) {
            $options->refResolver = new RefResolver($data);
        } else {
            $options->refResolver->setRootData($data);
        }

        if ($options->remoteRefProvider) {
            $options->refResolver->setRemoteRefProvider($options->remoteRefProvider);
        }

        $options->refResolver->preProcessReferences($data, $options);

        return $this->process($data, $options, '#');
    }


    /**
     * @param mixed $data
     * @param Context|null $options
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     */
    public function out($data, Context $options = null)
    {
        if ($options === null) {
            $options = new Context();
        }

        $options->circularReferences = new \SplObjectStorage();
        $options->import = false;
        return $this->process($data, $options);
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @throws InvalidValue
     * @throws \Exception
     */
    private function processType(&$data, Context $options, $path = '#')
    {
        if ($options->tolerateStrings && is_string($data)) {
            $valid = Type::readString($this->type, $data);
        } else {
            $valid = Type::isValid($this->type, $data, $options->version);
        }
        if (!$valid) {
            $this->fail((new TypeException(ucfirst(
                    implode(', ', is_array($this->type) ? $this->type : array($this->type))
                    . ' expected, ' . json_encode($data) . ' received')
            ))->withData($data)->withConstraint($this->type), $path);
        }
    }

    /**
     * @param mixed $data
     * @param string $path
     * @throws InvalidValue
     * @throws \Exception
     */
    private function processEnum($data, $path = '#')
    {
        $enumOk = false;
        foreach ($this->enum as $item) {
            if ($item === $data ||
                ( // Int and float equality check.
                    (is_int($item) || is_float($item)) &&
                    (is_int($data) || is_float($data)) &&
                    $item == $data
                )
            ) {
                $enumOk = true;
                break;
            } else {
                if (is_array($item) || is_object($item)) {
                    $diff = new JsonDiff($item, $data, JsonDiff::STOP_ON_DIFF);
                    if ($diff->getDiffCnt() === 0) {
                        $enumOk = true;
                        break;
                    }
                }
            }
        }
        if (!$enumOk) {
            $this->fail((new EnumException('Enum failed, enum: ' . json_encode($this->enum) . ', data: ' . json_encode($data)))
                ->withData($data)
                ->withConstraint($this->enum), $path);
        }
    }

    /**
     * @param mixed $data
     * @param string $path
     * @throws InvalidValue
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processConst($data, $path)
    {
        if ($this->const !== $data &&
            !( // Int and float equality.
                (is_int($this->const) || is_float($this->const)) &&
                (is_int($data) || is_float($data)) &&
                $this->const == $data
            )
        ) {
            if ((is_object($this->const) && is_object($data))
                || (is_array($this->const) && is_array($data))) {
                $diff = new JsonDiff($this->const, $data,
                    JsonDiff::STOP_ON_DIFF);
                if ($diff->getDiffCnt() != 0) {
                    $this->fail((new ConstException('Const failed'))
                        ->withData($data)
                        ->withConstraint($this->const), $path);
                }
            } else {
                $this->fail((new ConstException('Const failed'))
                    ->withData($data)
                    ->withConstraint($this->const), $path);
            }
        }
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processNot($data, Context $options, $path)
    {
        $exception = false;
        try {
            self::unboolSchema($this->not)->process($data, $options, $path . '->not');
        } catch (InvalidValue $exception) {
            // Expected exception
        }
        if ($exception === false) {
            $this->fail((new LogicException('Not ' . json_encode($this->not) . ' expected, ' . json_encode($data) . ' received'))
                ->withData($data), $path . '->not');
        }
    }

    /**
     * @param string $data
     * @param string $path
     * @throws InvalidValue
     */
    private function processString($data, $path)
    {
        if ($this->minLength !== null) {
            if (mb_strlen($data, 'UTF-8') < $this->minLength) {
                $this->fail((new StringException('String is too short', StringException::TOO_SHORT))
                    ->withData($data)->withConstraint($this->minLength), $path);
            }
        }
        if ($this->maxLength !== null) {
            if (mb_strlen($data, 'UTF-8') > $this->maxLength) {
                $this->fail((new StringException('String is too long', StringException::TOO_LONG))
                    ->withData($data)->withConstraint($this->maxLength), $path);
            }
        }
        if ($this->pattern !== null) {
            if (0 === preg_match(Helper::toPregPattern($this->pattern), $data)) {
                $this->fail((new StringException(json_encode($data) . ' does not match to '
                    . $this->pattern, StringException::PATTERN_MISMATCH))
                    ->withData($data)->withConstraint($this->pattern), $path);
            }
        }
        if ($this->format !== null) {
            $validationError = Format::validationError($this->format, $data);
            if ($validationError !== null) {
                if (!($this->format === "uri" && substr($path, -3) === ':id')) {
                    $this->fail((new StringException($validationError))
                        ->withData($data), $path);
                }
            }
        }
    }

    /**
     * @param float|int $data
     * @param string $path
     * @throws InvalidValue
     */
    private function processNumeric($data, $path)
    {
        if ($this->multipleOf !== null) {
            $div = $data / $this->multipleOf;
            if ($div != (int)$div && ($div = $data * (1 / $this->multipleOf)) && ($div != (int)$div)) {
                $this->fail((new NumericException($data . ' is not multiple of ' . $this->multipleOf, NumericException::MULTIPLE_OF))
                    ->withData($data)->withConstraint($this->multipleOf), $path);
            }
        }

        if ($this->exclusiveMaximum !== null && !is_bool($this->exclusiveMaximum)) {
            if ($data >= $this->exclusiveMaximum) {
                $this->fail((new NumericException(
                    'Value less or equal than ' . $this->exclusiveMaximum . ' expected, ' . $data . ' received',
                    NumericException::MAXIMUM))
                    ->withData($data)->withConstraint($this->exclusiveMaximum), $path);
            }
        }

        if ($this->exclusiveMinimum !== null && !is_bool($this->exclusiveMinimum)) {
            if ($data <= $this->exclusiveMinimum) {
                $this->fail((new NumericException(
                    'Value more or equal than ' . $this->exclusiveMinimum . ' expected, ' . $data . ' received',
                    NumericException::MINIMUM))
                    ->withData($data)->withConstraint($this->exclusiveMinimum), $path);
            }
        }

        if ($this->maximum !== null) {
            if ($this->exclusiveMaximum === true) {
                if ($data >= $this->maximum) {
                    $this->fail((new NumericException(
                        'Value less or equal than ' . $this->maximum . ' expected, ' . $data . ' received',
                        NumericException::MAXIMUM))
                        ->withData($data)->withConstraint($this->maximum), $path);
                }
            } else {
                if ($data > $this->maximum) {
                    $this->fail((new NumericException(
                        'Value less than ' . $this->maximum . ' expected, ' . $data . ' received',
                        NumericException::MAXIMUM))
                        ->withData($data)->withConstraint($this->maximum), $path);
                }
            }
        }

        if ($this->minimum !== null) {
            if ($this->exclusiveMinimum === true) {
                if ($data <= $this->minimum) {
                    $this->fail((new NumericException(
                        'Value more or equal than ' . $this->minimum . ' expected, ' . $data . ' received',
                        NumericException::MINIMUM))
                        ->withData($data)->withConstraint($this->minimum), $path);
                }
            } else {
                if ($data < $this->minimum) {
                    $this->fail((new NumericException(
                        'Value more than ' . $this->minimum . ' expected, ' . $data . ' received',
                        NumericException::MINIMUM))
                        ->withData($data)->withConstraint($this->minimum), $path);
                }
            }
        }
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processOneOf($data, Context $options, $path)
    {
        $successes = 0;
        $failures = '';
        $subErrors = [];
        $skipValidation = false;
        if ($options->skipValidation) {
            $skipValidation = true;
            $options->skipValidation = false;
        }

        $result = $data;
        foreach ($this->oneOf as $index => $item) {
            try {
                $result = self::unboolSchema($item)->process($data, $options, $path . '->oneOf[' . $index . ']');
                $successes++;
                if ($successes > 1 || $options->skipValidation) { // @phpstan-ignore-line
                    break;
                }
            } catch (InvalidValue $exception) {
                $subErrors[$index] = $exception;
                $failures .= ' ' . $index . ': ' . Helper::padLines(' ', $exception->getMessage()) . "\n";
                // Expected exception
            }
        }
        if ($skipValidation) {
            $options->skipValidation = true;
            if ($successes === 0) {
                $result = self::unboolSchema($this->oneOf[0])->process($data, $options, $path . '->oneOf[0]');
            }
        }

        if (!$options->skipValidation) {
            if ($successes === 0) {
                $exception = new LogicException('No valid results for oneOf {' . "\n" . substr($failures, 0, -1) . "\n}");
                $exception->error = 'No valid results for oneOf';
                $exception->subErrors = $subErrors;
                $exception->data = $data;
                $this->fail($exception, $path);
            } elseif ($successes > 1) {
                $exception = new LogicException('More than 1 valid result for oneOf: '
                    . $successes . '/' . count($this->oneOf) . ' valid results for oneOf {'
                    . "\n" . substr($failures, 0, -1) . "\n}");
                $exception->error = 'More than 1 valid result for oneOf';
                $exception->subErrors = $subErrors;
                $exception->data = $data;
                $this->fail($exception, $path);
            }
        }
        return $result;
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processAnyOf($data, Context $options, $path)
    {
        $successes = 0;
        $failures = '';
        $subErrors = [];
        $result = $data;
        foreach ($this->anyOf as $index => $item) {
            try {
                $result = self::unboolSchema($item)->process($data, $options, $path . '->anyOf[' . $index . ']');
                $successes++;
                if ($successes) {
                    break;
                }
            } catch (InvalidValue $exception) {
                $subErrors[$index] = $exception;
                $failures .= ' ' . $index . ': ' . $exception->getMessage() . "\n";
                // Expected exception
            }
        }
        if (!$successes && !$options->skipValidation) {
            $exception = new LogicException('No valid results for anyOf {' . "\n"
                . substr(Helper::padLines(' ', $failures, false), 0, -1)
                . "\n}");
            $exception->error = 'No valid results for anyOf';
            $exception->subErrors = $subErrors;
            $exception->data = $data;
            $this->fail($exception, $path);
        }
        return $result;
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processAllOf($data, Context $options, $path)
    {
        $result = $data;
        foreach ($this->allOf as $index => $item) {
            $result = self::unboolSchema($item)->process($data, $options, $path . '->allOf[' . $index . ']');
        }
        return $result;
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processIf($data, Context $options, $path)
    {
        $valid = true;
        try {
            self::unboolSchema($this->if)->process($data, $options, $path . '->if');
        } catch (InvalidValue $exception) {
            $valid = false;
        }
        if ($valid) {
            if ($this->then !== null) {
                return self::unboolSchema($this->then)->process($data, $options, $path . '->then');
            }
        } else {
            if ($this->else !== null) {
                return self::unboolSchema($this->else)->process($data, $options, $path . '->else');
            }
        }
        return null;
    }

    /**
     * @param array $array
     * @param Context $options
     * @param string $path
     * @throws InvalidValue
     */
    private function processObjectRequired($array, Context $options, $path)
    {
        foreach ($this->required as $item) {
            if (!array_key_exists($item, $array)) {
                $this->fail(
                    (new ObjectException(
                        'Required property missing: ' . $item . ', data: ' . json_encode($array, JSON_UNESCAPED_SLASHES),
                        ObjectException::REQUIRED))
                        ->withData((object)$array)->withConstraint($item),
                    $path);
            }
        }
    }

    /**
     * @param object $data
     * @param Context $options
     * @param string $path
     * @param ObjectItemContract|null $result
     * @return array|null|ClassStructure|ObjectItemContract|SchemaContract
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processObject($data, Context $options, $path, $result = null)
    {
        $import = $options->import;

        $hasMapping = $this->properties !== null && isset($this->properties->__dataToProperty[$options->mapping]);

        $array = !$data instanceof \stdClass ? get_object_vars($data) : (array)$data;

        // convert imported data to default mapping before validation
        if ($import && $options->mapping !== self::DEFAULT_MAPPING) {
            if ($this->properties !== null && isset($this->properties->__dataToProperty[$options->mapping])) {
                foreach ($this->properties->__dataToProperty[$options->mapping] as $dataName => $propertyName) {
                    if (!isset($array[$dataName])) {
                        continue;
                    }

                    $propertyName = isset($this->properties->__propertyToData[self::DEFAULT_MAPPING][$propertyName])
                        ? $this->properties->__propertyToData[self::DEFAULT_MAPPING][$propertyName]
                        : $propertyName;
                    if ($propertyName !== $dataName) {
                        $array[$propertyName] = $array[$dataName];
                        unset($array[$dataName]);
                    }
                }
            }
        }

        if (!$options->skipValidation && $this->required !== null) {
            $this->processObjectRequired($array, $options, $path);
        }

        // build result entity
        if ($import) {
            if (!$options->validateOnly) {

                if ($this->useObjectAsArray) {
                    $result = array();
                } else {
                    //* todo check performance impact
                    if (null === $this->objectItemClass) {
                        if (!$result instanceof ObjectItemContract) {
                            $result = new ObjectItem();
                            $result->setDocumentPath($path);
                        }
                    } else {
                        $className = $this->objectItemClass;
                        if ($options->objectItemClassMapping !== null) {
                            if (isset($options->objectItemClassMapping[$className])) {
                                $className = $options->objectItemClassMapping[$className];
                            }
                        }
                        if (null === $result || get_class($result) !== $className) {
                            $result = new $className;
                            //* todo check performance impact
                            if ($result instanceof ClassStructure) {
                                $result->setDocumentPath($path);
                                if ($result->__validateOnSet) {
                                    $result->__validateOnSet = false;
                                    /** @noinspection PhpUnusedLocalVariableInspection */
                                    /* todo check performance impact
                                    $validateOnSetHandler = new ScopeExit(function () use ($result) {
                                        $result->__validateOnSet = true;
                                    });
                                    //*/
                                }
                            }
                            //*/
                        }
                    }
                    //*/
                }
            }
        }

        // @todo better check for schema id

        if ($import
            && isset($array[Schema::PROP_ID_D4])
            && ($options->version === Schema::VERSION_DRAFT_04 || $options->version === Schema::VERSION_AUTO)
            && is_string($array[Schema::PROP_ID_D4])) {
            $id = $array[Schema::PROP_ID_D4];
            $refResolver = $options->refResolver;
            $parentScope = $refResolver->updateResolutionScope($id);
            /** @noinspection PhpUnusedLocalVariableInspection */
            $defer = new ScopeExit(function () use ($parentScope, $refResolver) {
                $refResolver->setResolutionScope($parentScope);
            });
        }

        if ($import
            && isset($array[self::PROP_ID])
            && ($options->version >= Schema::VERSION_DRAFT_06 || $options->version === Schema::VERSION_AUTO)
            && is_string($array[self::PROP_ID])) {
            $id = $array[self::PROP_ID];
            $refResolver = $options->refResolver;
            $parentScope = $refResolver->updateResolutionScope($id);
            /** @noinspection PhpUnusedLocalVariableInspection */
            $defer = new ScopeExit(function () use ($parentScope, $refResolver) {
                $refResolver->setResolutionScope($parentScope);
            });
        }

        // check $ref
        if ($import) {
            try {

                $refProperty = null;
                $dereference = $options->dereference;

                if ($this->properties !== null && isset($array[self::PROP_REF])) {
                    $refPropName = self::PROP_REF;
                    if ($hasMapping) {
                        if (isset($this->properties->__dataToProperty[$options->mapping][self::PROP_REF])) {
                            $refPropName = $this->properties->__dataToProperty[$options->mapping][self::PROP_REF];
                        }
                    }

                    $refProperty = $this->properties[$refPropName];

                    if (isset($refProperty)) {
                        $dereference = $refProperty->format === Format::URI_REFERENCE;
                    }
                }

                if (
                    isset($array[self::PROP_REF])
                    && is_string($array[self::PROP_REF])
                    && $dereference
                ) {
                    $refString = $array[self::PROP_REF];

                    // todo check performance impact
                    if ($refString === 'http://json-schema.org/draft-04/schema#'
                        || $refString === 'http://json-schema.org/draft-06/schema#'
                        || $refString === 'http://json-schema.org/draft-07/schema#') {
                        return Schema::schema();
                    }

                    // TODO consider process # by reference here ?
                    $refResolver = $options->refResolver;
                    $preRefScope = $refResolver->getResolutionScope();
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $deferRefScope = new ScopeExit(function () use ($preRefScope, $refResolver) {
                        $refResolver->setResolutionScope($preRefScope);
                    });

                    $ref = $refResolver->resolveReference($refString);
                    $unresolvedData = $data;
                    $data = self::unboolSchemaData($ref->getData());
                    if (!$options->validateOnly) {
                        if ($ref->isImported()) {
                            $refResult = $ref->getImported();
                            return $refResult;
                        }
                        $ref->setImported($result);
                        try {
                            // Best effort dereference delivery.
                            $refResult = $this->process($data, $options, $path . '->$ref:' . $refString, $result);
                            if ($refResult instanceof ObjectItemContract) {
                                if ($refResult->getFromRefs()) {
                                    $refResult = clone $refResult; // @todo check performance, consider option
                                }
                                $refResult->setFromRef($refString);
                            }
                            $ref->setImported($refResult);
                            return $refResult;
                        } catch (InvalidValue $exception) {
                            if ($this->objectItemClass === 'Swaggest\JsonSchema\Schema') {
                                throw $exception;
                            }

                            $ref->unsetImported();
                            $skipValidation = $options->skipValidation;
                            $options->skipValidation = true;
                            $refResult = $this->process($data, $options, $path . '->$ref:' . $refString);
                            if ($refResult instanceof ObjectItemContract) {
                                if ($refResult->getFromRefs()) {
                                    $refResult = clone $refResult; // @todo check performance, consider option
                                }
                                $refResult->setFromRef($refString);
                            }
                            $options->skipValidation = $skipValidation;

                            if ($result instanceof WithResolvedValue) {
                                $result->setResolvedValue($refResult);
                            }

                            // Proceeding with unresolved data.
                            $data = $unresolvedData;
                        }
                    } else {
                        $this->process($data, $options, $path . '->$ref:' . $refString);
                    }
                }
            } catch (InvalidValue $exception) {
                $this->fail($exception, $path);
            }
        }

        /** @var Schema[]|null $properties */
        $properties = null;

        $nestedProperties = null;
        if ($this->properties !== null) {
            $properties = $this->properties->toArray(); // todo call directly
            $nestedProperties = $this->properties->nestedProperties;
        }


        if (!$options->skipValidation) {
            if ($this->minProperties !== null && count($array) < $this->minProperties) {
                $this->fail((new ObjectException("Not enough properties", ObjectException::TOO_FEW))
                    ->withData($data)->withConstraint($this->minProperties), $path);
            }
            if ($this->maxProperties !== null && count($array) > $this->maxProperties) {
                $this->fail((new ObjectException("Too many properties", ObjectException::TOO_MANY))
                    ->withData($data)->withConstraint($this->maxProperties), $path);
            }
            if ($this->propertyNames !== null) {
                $propertyNames = self::unboolSchema($this->propertyNames);
                foreach ($array as $key => $tmp) {
                    $propertyNames->process($key, $options, $path . '->propertyNames:' . $key);
                }
            }
        }

        $defaultApplied = array();
        if ($import
            && !$options->validateOnly
            && $options->applyDefaults
            && $properties !== null
        ) {
            foreach ($properties as $key => $property) {
                $allowNull = false;
                if ($property instanceof HasDefault) {
                    if (!$property->hasDefault()) {
                        continue;
                    }

                    $allowNull = true;
                }

                // todo check when property is \stdClass `{}` here (RefTest)
                if ($property instanceof SchemaContract) {
                    if (!array_key_exists($key, $array)) {
                        $default = $property->getDefault();
                        if (null === $default && !$allowNull) { // @phpstan-ignore-line
                            continue;
                        }

                        $defaultApplied[$key] = true;
                        $array[$key] = $default;
                    }
                }
            }
        }

        /**
         * @var string $key
         * @var mixed $value
         */
        foreach ($array as $key => $value) {
            if ($key === '' && PHP_VERSION_ID < 70100) {
                $this->fail(new InvalidValue('Empty property name'), $path);
            }

            $found = false;

            if (!$options->skipValidation && !empty($this->dependencies)) {
                $deps = $this->dependencies;
                if (isset($deps->$key)) {
                    $dependencies = $deps->$key;
                    $dependencies = self::unboolSchema($dependencies);
                    if ($dependencies instanceof SchemaContract) {
                        $dependencies->process($data, $options, $path . '->dependencies:' . $key);
                    } else {
                        foreach ($dependencies as $item) {
                            if (!array_key_exists($item, $array)) {
                                $this->fail((new ObjectException('Dependency property missing: ' . $item,
                                    ObjectException::DEPENDENCY_MISSING))
                                    ->withData($data)->withConstraint($item), $path);
                            }
                        }
                    }
                }
            }

            $propertyFound = false;
            if (isset($properties[$key])) {
                /** @var Schema[] $properties */
                $prop = self::unboolSchema($properties[$key]);
                $propertyFound = true;
                $found = true;
                if ($prop instanceof SchemaContract) {
                    $value = $prop->process(
                        $value,
                        isset($defaultApplied[$key]) ? $options->withDefault() : $options,
                        $path . '->properties:' . $key
                    );
                }
            }

            /** @var Egg[] $nestedEggs */
            $nestedEggs = null;
            if (isset($nestedProperties[$key])) {
                $found = true;
                $nestedEggs = $nestedProperties[$key];
                // todo iterate all nested props?
                $value = self::unboolSchema($nestedEggs[0]->propertySchema)->process($value, $options, $path . '->nestedProperties:' . $key);
            }

            if ($this->patternProperties !== null) {
                foreach ($this->patternProperties as $pattern => $propertySchema) {
                    if (preg_match(Helper::toPregPattern($pattern), $key)) {
                        $found = true;
                        $value = self::unboolSchema($propertySchema)->process($value, $options,
                            $path . '->patternProperties[' . strtr($pattern, array('~' => '~1', ':' => '~2')) . ']:' . $key);
                        if (!$options->validateOnly && $import) {
                            $result->addPatternPropertyName($pattern, $key);
                        }
                        //break; // todo manage multiple import data properly (pattern accessor)
                    }
                }
            }
            if (!$found && $this->additionalProperties !== null) {
                if (!$options->skipValidation && $this->additionalProperties === false) {
                    $this->fail((new ObjectException('Additional properties not allowed: ' . $key))
                        ->withData($data), $path);
                }

                if ($this->additionalProperties instanceof SchemaContract) {
                    $value = $this->additionalProperties->process($value, $options, $path . '->additionalProperties:' . $key);
                }

                if ($import && !$this->useObjectAsArray && !$options->validateOnly) {
                    $result->addAdditionalPropertyName($key);
                }
            }

            $propertyName = $key;

            if ($hasMapping) {
                if ($this->properties !== null && isset($this->properties->__dataToProperty[self::DEFAULT_MAPPING][$key])) {
                    // todo check performance of local map access
                    $propertyName = $this->properties->__dataToProperty[self::DEFAULT_MAPPING][$key];
                }
            }

            if ($options->mapping !== self::DEFAULT_MAPPING) {
                if (!$import) {
                    if ($this->properties !== null && isset($this->properties->__propertyToData[$options->mapping][$propertyName])) {
                        // todo check performance of local map access
                        $propertyName = $this->properties->__propertyToData[$options->mapping][$propertyName];
                    }
                }
            }

            if (!$options->validateOnly && $nestedEggs && $import) {
                foreach ($nestedEggs as $nestedEgg) {
                    $result->setNestedProperty($key, $value, $nestedEgg);
                }
                if ($propertyFound) {
                    $result->$propertyName = $value;
                }
            } else {
                if (!$import && $hasMapping) {
                    if ($this->properties !== null && isset($this->properties->__propertyToData[$options->mapping][$propertyName])) {
                        $propertyName = $this->properties->__propertyToData[$options->mapping][$propertyName];
                    }
                }

                if ($this->useObjectAsArray && $import) {
                    $result[$propertyName] = $value;
                } else {
                    if ($found || !$import) {
                        $result->$propertyName = $value;
                    } elseif (!isset($result->$propertyName)) {
                        if (self::PROP_REF !== $propertyName || empty($result->__fromRef)) {
                            $result->$propertyName = $value;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @param Context $options
     * @param string $path
     * @param array $result
     * @return mixed
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    private function processArray($data, Context $options, $path, $result)
    {
        $count = count($data);
        if (!$options->skipValidation) {
            if ($this->minItems !== null && $count < $this->minItems) {
                $this->fail((new ArrayException("Not enough items in array"))->withData($data), $path);
            }

            if ($this->maxItems !== null && $count > $this->maxItems) {
                $this->fail((new ArrayException("Too many items in array"))->withData($data), $path);
            }
        }

        $pathItems = 'items';
        $this->items = self::unboolSchema($this->items);
        if ($this->items instanceof SchemaContract) {
            $items = array();
            /**
             * @var null|bool|Schema $additionalItems
             */
            $additionalItems = $this->items;
        } elseif ($this->items === null) { // items defaults to empty schema so everything is valid
            $items = array();
            $additionalItems = true;
        } else { // listed items
            $items = $this->items;
            $additionalItems = $this->additionalItems;
            $pathItems = 'additionalItems';
        }

        /**
         * @var Schema|Schema[] $items
         */
        $itemsLen = is_array($items) ? count($items) : 0;
        $index = 0;
        foreach ($result as $key => $value) {
            if ($index < $itemsLen) {
                $itemSchema = self::unboolSchema($items[$index]);
                $result[$key] = $itemSchema->process($value, $options, $path . '->items:' . $index);
            } else {
                if ($additionalItems instanceof SchemaContract) {
                    $result[$key] = $additionalItems->process($value, $options, $path . '->' . $pathItems
                        . '[' . $index . ']:' . $index);
                } elseif (!$options->skipValidation && $additionalItems === false) {
                    $this->fail((new ArrayException('Unexpected array item'))->withData($data), $path);
                }
            }
            ++$index;
        }

        if (!$options->skipValidation && $this->uniqueItems) {
            if (!UniqueItems::isValid($data)) {
                $this->fail((new ArrayException('Array is not unique'))->withData($data), $path);
            }
        }

        if (!$options->skipValidation && $this->contains !== null) {
            /** @var Schema|bool $contains */
            $contains = $this->contains;
            if ($contains === false) {
                $this->fail((new ArrayException('Contains is false'))->withData($data), $path);
            }
            if ($count === 0) {
                $this->fail((new ArrayException('Empty array fails contains constraint')), $path);
            }
            if ($contains === true) {
                $contains = self::unboolSchema($contains);
            }
            $containsOk = false;
            foreach ($data as $key => $item) {
                try {
                    $contains->process($item, $options, $path . '->' . $key);
                    $containsOk = true;
                    break;
                } catch (InvalidValue $exception) {
                }
            }
            if (!$containsOk) {
                $this->fail((new ArrayException('Array fails contains constraint'))
                    ->withData($data), $path);
            }
        }
        return $result;
    }

    /**
     * @param mixed|string $data
     * @param Context $options
     * @param string $path
     * @return bool|mixed|string
     * @throws InvalidValue
     */
    private function processContent($data, Context $options, $path)
    {
        try {
            if ($options->unpackContentMediaType) {
                return Content::process($options, $this->contentEncoding, $this->contentMediaType, $data, $options->import);
            } else {
                Content::process($options, $this->contentEncoding, $this->contentMediaType, $data, true);
            }
        } catch (InvalidValue $exception) {
            $this->fail($exception, $path);
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @param Context $options
     * @param string $path
     * @param mixed|null $result
     * @return array|mixed|null|object|\stdClass
     * @throws InvalidValue
     * @throws \Exception
     * @throws \Swaggest\JsonDiff\Exception
     */
    public function process($data, Context $options, $path = '#', $result = null)
    {
        $origData = $data;
        $import = $options->import;

        if (!$import && $data instanceof SchemaExporter) {
            $data = $data->exportSchema(); // Used to export ClassStructure::schema()
        }

        if (!$import && $data instanceof ObjectItemContract) {
            $result = new \stdClass();

            if ('#' === $path) {
                $injectDefinitions = new ScopeExit(function () use ($result, $options) {
                    foreach ($options->exportedDefinitions as $ref => $data) {
                        if ($data !== null && ($ref[0] === '#' || $ref[1] === '/')) {
                            JsonPointer::add($result, JsonPointer::splitPath($ref), $data,
                                /*JsonPointer::SKIP_IF_ISSET + */
                                JsonPointer::RECURSIVE_KEY_CREATION);
                        }
                    }
                });
            }

            if ($options->isRef) {
                $options->isRef = false;
            } else {
                if ('#' !== $path && $refs = $data->getFromRefs()) {
                    $ref = $refs[0];
                    if (!array_key_exists($ref, $options->exportedDefinitions) && strpos($ref, '://') === false) {
                        $exported = null;
                        $options->exportedDefinitions[$ref] = &$exported;
                        $options->isRef = true;
                        $exported = $this->process($data, $options, $ref);
                        unset($exported);
                    }

                    $countRefs = count($refs);
                    for ($i = 1; $i < $countRefs; $i++) {
                        $ref = $refs[$i];
                        if (!array_key_exists($ref, $options->exportedDefinitions) && strpos($ref, '://') === false) {
                            $exported = new \stdClass();
                            $exported->{self::PROP_REF} = $refs[$i - 1];
                            $options->exportedDefinitions[$ref] = $exported;
                        }
                    }

                    $result->{self::PROP_REF} = $refs[$countRefs - 1];
                    return $result;
                }
            }

            if ($options->circularReferences->contains($data)) {
                /** @noinspection PhpIllegalArrayKeyTypeInspection */
                $path = $options->circularReferences[$data];
                $result->{self::PROP_REF} = PointerUtil::getDataPointer($path, true);
                return $result;
            }
            $options->circularReferences->attach($data, $path);

            $data = $data->jsonSerialize();
        }

        $path .= $this->getFromRefPath();

        if (!$import && is_array($data) && $this->useObjectAsArray) {
            $data = (object)$data;
        }

        if (null !== $options->dataPreProcessor) {
            $data = $options->dataPreProcessor->process($data, $this, $import);
        }

        if ($options->skipValidation) {
            goto skipValidation;
        }

        if ($this->type !== null) {
            $this->processType($data, $options, $path);
        }

        if ($this->enum !== null) {
            $this->processEnum($data, $path);
        }

        if (array_key_exists(self::CONST_PROPERTY, $this->__arrayOfData)) {
            $this->processConst($data, $path);
        }

        if ($this->not !== null) {
            $this->processNot($data, $options, $path);
        }

        if (is_string($data)) {
            $this->processString($data, $path);
        }

        if (is_int($data) || is_float($data)) {
            $this->processNumeric($data, $path);
        }

        if ($this->if !== null) {
            $result = $this->processIf($data, $options, $path);
        }

        skipValidation:

        if ($result === null) {
            $result = $data;
        }

        if ($this->oneOf !== null) {
            $result = $this->processOneOf($data, $options, $path);
        }

        if ($this->anyOf !== null) {
            $result = $this->processAnyOf($data, $options, $path);
        }

        if ($this->allOf !== null) {
            $result = $this->processAllOf($data, $options, $path);
        }

        if (is_object($data)) {
            $result = $this->processObject($data, $options, $path, $result);
        }

        if (is_array($data)) {
            $result = $this->processArray($data, $options, $path, $result);
        }

        if ($this->contentEncoding !== null || $this->contentMediaType !== null) {
            if ($import && !is_string($data)) {
                return $result;
            }
            $result = $this->processContent($data, $options, $path);
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
     * @param InvalidValue $exception
     * @param string $path
     * @throws InvalidValue
     */
    private function fail(InvalidValue $exception, $path)
    {
        $exception->addPath($path);
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


    /**
     * @param Properties $properties
     * @return SchemaContract
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param string $name
     * @param Schema $schema
     * @return $this
     */
    public function setProperty($name, $schema)
    {
        if (null === $this->properties) {
            $this->properties = new Properties();
        }
        $this->properties->__set($name, $schema);
        return $this;
    }

    /**
     * @param string $name
     * @param SchemaContract $schema
     * @return $this
     * @throws Exception
     */
    public function setPatternProperty($name, $schema)
    {
        if (null === $this->patternProperties) {
            $this->patternProperties = new Properties();
        }
        $this->patternProperties->__set($name, $schema);
        return $this;
    }


    /** @var mixed[] */
    private $metaItems = array();

    public function addMeta($meta, $name = null)
    {
        if ($name === null) {
            $name = get_class($meta);
        }
        $this->metaItems[$name] = $meta;
        return $this;
    }

    public function getMeta($name)
    {
        if (isset($this->metaItems[$name])) {
            return $this->metaItems[$name];
        }
        return null;
    }

    /**
     * @param Context $options
     * @return ObjectItemContract
     */
    public function makeObjectItem(Context $options = null)
    {
        if (null === $this->objectItemClass) {
            return new ObjectItem();
        } else {
            $className = $this->objectItemClass;
            if ($options !== null) {
                if (isset($options->objectItemClassMapping[$className])) {
                    $className = $options->objectItemClassMapping[$className];
                }
            }
            return new $className;
        }
    }

    /**
     * Resolves boolean schema into Schema instance.
     *
     * @param mixed $schema
     * @return mixed|Schema
     */
    public static function unboolSchema($schema)
    {
        static $trueSchema;
        static $falseSchema;

        if (null === $trueSchema) {
            $trueSchema = new Schema();
            $trueSchema->__booleanSchema = true;
            $falseSchema = new Schema();
            $falseSchema->not = $trueSchema;
            $falseSchema->__booleanSchema = false;
        }

        if ($schema === true) {
            return $trueSchema;
        } elseif ($schema === false) {
            return $falseSchema;
        } else {
            return $schema;
        }
    }

    /**
     * Converts bool value into an object schema.
     *
     * @param mixed $data
     * @return \stdClass
     */
    public static function unboolSchemaData($data)
    {
        static $trueSchema;
        static $falseSchema;

        if (null === $trueSchema) {
            $trueSchema = new \stdClass();
            $falseSchema = new \stdClass();
            $falseSchema->not = $trueSchema;
        }

        if ($data === true) {
            return $trueSchema;
        } elseif ($data === false) {
            return $falseSchema;
        } else {
            return $data;
        }
    }

    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function hasDefault()
    {
        return array_key_exists(self::DEFAULT_PROPERTY, $this->__arrayOfData);
    }

    /**
     * @nolint
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function getObjectItemClass()
    {
        return $this->objectItemClass;
    }

    /**
     * @return string[]
     */
    public function getPropertyNames()
    {
        if (null === $this->properties) {
            return array();
        }
        return array_keys($this->properties->toArray());
    }

    /**
     * @return string[]
     */
    public function getNestedPropertyNames()
    {
        if (null === $this->properties) {
            return array();
        }
        return $this->properties->nestedPropertyNames;
    }

}
