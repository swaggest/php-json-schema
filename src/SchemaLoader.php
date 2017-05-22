<?php

namespace Swaggest\JsonSchema;

use PhpLang\ScopeExit;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Constraint\Ref;
use Swaggest\JsonSchema\Constraint\Type;
use Swaggest\JsonSchema\RemoteRef\BasicFetcher;

/**
 * Class SchemaLoader
 * @package Swaggest\JsonSchema
 * @deprecated
 */
class SchemaLoader
{
    const ID = 'id';

    const TYPE = 'type';

    const PROPERTIES = 'properties';
    const PATTERN_PROPERTIES = 'patternProperties';
    const ADDITIONAL_PROPERTIES = 'additionalProperties';
    const REQUIRED = 'required';
    const DEPENDENCIES = 'dependencies';
    const MIN_PROPERTIES = 'minProperties';
    const MAX_PROPERTIES = 'maxProperties';

    const REF = '$ref';

    const ITEMS = 'items';
    const ADDITIONAL_ITEMS = 'additionalItems';
    const UNIQUE_ITEMS = 'uniqueItems';
    const MIN_ITEMS = 'minItems';
    const MAX_ITEMS = 'maxItems';

    const ENUM = 'enum';

    const MINIMUM = 'minimum';
    const EXCLUSIVE_MINIMUM = 'exclusiveMinimum';
    const MAXIMUM = 'maximum';
    const EXCLUSIVE_MAXIMUM = 'exclusiveMaximum';
    const MULTIPLE_OF = 'multipleOf';

    const PATTERN = 'pattern';
    const MIN_LENGTH = 'minLength';
    const MAX_LENGTH = 'maxLength';

    const NOT = 'not';
    const ALL_OF = 'allOf';
    const ANY_OF = 'anyOf';
    const ONE_OF = 'oneOf';

    /** @var Schema */
    private $rootSchema;

    private $rootData;

    /** @var Ref[] */
    private $refs = array();

    /** @var SchemaLoader[] */
    private $remoteSchemaLoaders = array();

    /** @var RemoteRefProvider */
    private $refProvider;

    public function setRemoteRefProvider(RemoteRefProvider $provider)
    {
        $this->refProvider = $provider;
        return $this;
    }

    private function getRefProvider()
    {
        if (null === $this->refProvider) {
            $this->refProvider = new BasicFetcher();
        }
        return $this->refProvider;
    }

    public function readSchema($schemaData)
    {
        return $this->readSchemaDeeper($schemaData);
    }

    /** @var \SplObjectStorage */
    private $circularReferences;
    public function dumpSchema(Schema $schema)
    {
        $this->circularReferences = new \SplObjectStorage();
        $this->dumpDefinitions = array();
        $this->dumpDefIndex = 0;
        $contents = $this->dumpSchemaDeeper($schema, '#');
        return $contents;
    }

    private function dumpSchemaDeeper(Schema $schema, $path)
    {
        $result = new \stdClass();

        if ($this->circularReferences->contains($schema)) {
            $path = $this->circularReferences[$schema];
            $result->{self::REF} = $path;
            return $result;
        }
        $this->circularReferences->attach($schema, $path);

        $data = get_object_vars($schema);
        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($value instanceof Schema) {
                $value = $this->dumpSchemaDeeper($value, $path . '/' . $key);
            }

            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($v instanceof Schema) {
                        $value[$k] = $this->dumpSchemaDeeper($v, $path . '/' . $key . '/' . $k);
                    }
                }
            }

            if ($key === self::PROPERTIES) {
                /** @var Properties $properties */
                $properties = $value;
                $value = array();
                foreach ($properties->toArray() as $propertyName => $property) {
                    $value[$propertyName] = $this->dumpSchemaDeeper($property, $path . '/' . $key . '/' . $propertyName);
                }
            }


            $result->$key = $value;
        }
        return $result;
    }

    private $resolutionScope;

    protected function readSchemaDeeper($schemaArray)
    {
        $schema = new Schema();
        if (null === $this->rootSchema) {
            $this->rootSchema = $schema;
            $this->rootData = $schemaArray;
        }

        if ($schemaArray instanceof \stdClass) {
            $schemaArray = (array)$schemaArray;
        }

        if (isset($schemaArray[self::ID])) {
            $parentScope = $this->resolutionScope;
            $this->resolutionScope = Helper::resolveURI($parentScope, $schemaArray[self::ID]);
            /** @noinspection PhpUnusedLocalVariableInspection */
            $defer = new ScopeExit(function () use ($parentScope) {
                $this->resolutionScope = $parentScope;
            });
        }

        if (isset($schemaArray[self::TYPE])) {
            $schema->type = $schemaArray[self::TYPE];
        }


        // Object
        if (isset($schemaArray[self::PROPERTIES])) {
            $properties = new Properties();
            $schema->properties = $properties;
            foreach ($schemaArray[self::PROPERTIES] as $name => $data) {
                $properties->__set($name, $this->readSchemaDeeper($data));
            }
        }

        if (isset($schemaArray[self::PATTERN_PROPERTIES])) {
            foreach ($schemaArray[self::PATTERN_PROPERTIES] as $name => $data) {
                $schema->patternProperties[$name] = $this->readSchemaDeeper($data);
            }
        }

        if (isset($schemaArray[self::ADDITIONAL_PROPERTIES])) {
            $additionalProperties = $schemaArray[self::ADDITIONAL_PROPERTIES];
            if ($additionalProperties instanceof \stdClass) {
                $schema->additionalProperties = $this->readSchemaDeeper($additionalProperties);
            } elseif (is_bool($additionalProperties)) {
                $schema->additionalProperties = $additionalProperties;
            } else {
                throw new InvalidValue('Object or boolean required for additionalProperties', InvalidValue::INVALID_VALUE);
            }
        }

        if (isset($schemaArray[self::REQUIRED])) {
            $schema->required = $schemaArray[self::REQUIRED];
        }

        if (isset($schemaArray[self::DEPENDENCIES])) {
            foreach ($schemaArray[self::DEPENDENCIES] as $key => $value) {
                if ($value instanceof \stdClass) {
                    $schema->dependencies[$key] = $this->readSchemaDeeper($value);
                } else {
                    $schema->dependencies[$key] = $value;
                }
            }
        }

        if (isset($schemaArray[self::MIN_PROPERTIES])) {
            $schema->minProperties = $schemaArray[self::MIN_PROPERTIES];
        }
        if (isset($schemaArray[self::MAX_PROPERTIES])) {
            $schema->maxProperties = $schemaArray[self::MAX_PROPERTIES];
        }


        // Array
        if (isset($schemaArray[self::ITEMS])) {
            $items = $schemaArray[self::ITEMS];
            if (is_array($items)) {
                $schema->items = array();
                foreach ($items as $item) {
                    $schema->items[] = $this->readSchemaDeeper($item);
                }
            } elseif ($items instanceof \stdClass) {
                $schema->items = $this->readSchemaDeeper($items);
            }
        }


        if (isset($schemaArray[self::ADDITIONAL_ITEMS])) {
            $additionalItems = $schemaArray[self::ADDITIONAL_ITEMS];
            if ($additionalItems instanceof \stdClass) {
                $schema->additionalItems = $this->readSchemaDeeper($additionalItems);
            } else {
                $schema->additionalItems = $additionalItems;
            }
        }

        if (isset($schemaArray[self::UNIQUE_ITEMS]) && $schemaArray[self::UNIQUE_ITEMS] === true) {
            $schema->uniqueItems = true;
        }

        if (isset($schemaArray[self::MIN_ITEMS])) {
            $schema->minItems = $schemaArray[self::MIN_ITEMS];
        }

        if (isset($schemaArray[self::MAX_ITEMS])) {
            $schema->maxItems = $schemaArray[self::MAX_ITEMS];
        }


        // Number
        if (isset($schemaArray[self::MINIMUM])) {
            $schema->minimum = $schemaArray[self::MINIMUM];
        }
        if (isset($schemaArray[self::EXCLUSIVE_MINIMUM])) {
            $schema->exclusiveMinimum = $schemaArray[self::EXCLUSIVE_MINIMUM];
        }
        if (isset($schemaArray[self::MAXIMUM])) {
            $schema->maximum = $schemaArray[self::MAXIMUM];
        }
        if (isset($schemaArray[self::EXCLUSIVE_MAXIMUM])) {
            $schema->exclusiveMaximum = $schemaArray[self::EXCLUSIVE_MAXIMUM];
        }
        if (isset($schemaArray[self::MULTIPLE_OF])) {
            $schema->multipleOf = $schemaArray[self::MULTIPLE_OF];
        }


        // String
        if (isset($schemaArray[self::PATTERN])) {
            $schema->pattern = $schemaArray[self::PATTERN];

        }
        if (isset($schemaArray[self::MIN_LENGTH])) {
            $schema->minLength = $schemaArray[self::MIN_LENGTH];
        }
        if (isset($schemaArray[self::MAX_LENGTH])) {
            $schema->maxLength = $schemaArray[self::MAX_LENGTH];
        }


        // Misc
        if (isset($schemaArray[self::ENUM])) {
            $schema->enum = $schemaArray[self::ENUM];
        }

        // Logic
        if (isset($schemaArray[self::ALL_OF])) {
            foreach ($schemaArray[self::ALL_OF] as $item) {
                $schema->allOf[] = $this->readSchemaDeeper($item);
            }
        }
        if (isset($schemaArray[self::ANY_OF])) {
            foreach ($schemaArray[self::ANY_OF] as $item) {
                $schema->anyOf[] = $this->readSchemaDeeper($item);
            }
        }
        if (isset($schemaArray[self::ONE_OF])) {
            foreach ($schemaArray[self::ONE_OF] as $item) {
                $schema->oneOf[] = $this->readSchemaDeeper($item);
            }
        }
        if (isset($schemaArray[self::NOT])) {
            $schema->not = $this->readSchemaDeeper($schemaArray[self::NOT]);
        }

        // should resolve references on load
        if (isset($schemaArray[self::REF])) {
            $schema->ref = $this->resolveReference($schemaArray[self::REF]);
        }

        return $schema;
    }


    /**
     * @param $referencePath
     * @return Ref
     * @throws \Exception
     */
    private function resolveReference($referencePath)
    {
        $ref = &$this->refs[$referencePath];
        if (null === $ref) {
            if ($referencePath[0] === '#') {
                if ($referencePath === '#') {
                    $ref = new Ref($referencePath, $this->rootSchema);
                } else {
                    $ref = new Ref($referencePath);
                    $path = explode('/', trim($referencePath, '#/'));
                    $branch = &$this->rootData;
                    while (!empty($path)) {
                        $folder = array_shift($path);

                        // unescaping special characters
                        // https://tools.ietf.org/html/draft-ietf-appsawg-json-pointer-07#section-4
                        // https://github.com/json-schema-org/JSON-Schema-Test-Suite/issues/130
                        $folder = str_replace(array('~0', '~1', '%25'), array('~', '/', '%'), $folder);

                        if ($branch instanceof \stdClass && isset($branch->$folder)) {
                            $branch = &$branch->$folder;
                        } elseif (is_array($branch) && isset($branch[$folder])) {
                            $branch = &$branch[$folder];
                        } else {
                            throw new \Exception('Could not resolve ' . $referencePath . ': ' . $folder);
                        }
                    }
                    $ref->setData($this->readSchema($branch));
                }
            } else {
                $refParts = explode('#', $referencePath);
                $url = Helper::resolveURI($this->resolutionScope, $refParts[0]);
                $url = rtrim($url, '#');
                $refLocalPath = isset($refParts[1]) ? '#' . $refParts[1] : '#';
                $schemaLoader = &$this->remoteSchemaLoaders[$url];
                if (null === $schemaLoader) {
                    $schemaLoader = SchemaLoader::create();
                    $schemaLoader->readSchema($this->getRefProvider()->getSchemaData($url));
                }

                $ref = $schemaLoader->resolveReference($refLocalPath);
            }
        }

        return $this->refs[$referencePath];
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static;
    }
}
