<?php

namespace Yaoi\Schema;

use Yaoi\Schema\Base;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Schema;

class SchemaLoader extends Base
{
    const TYPE = 'type';

    const PROPERTIES = 'properties';
    const ADDITIONAL_PROPERTIES = 'additionalProperties';
    const REF = '$ref';

    const ITEMS = 'items';
    const ADDITIONAL_ITEMS = 'additionalItems';
    const UNIQUE_ITEMS = 'uniqueItems';

    const MINIMUM = 'minimum';
    const EXCLUSIVE_MINIMUM = 'exclusiveMinimum';
    const MAXIMUM = 'maximum';
    const EXCLUSIVE_MAXIMUM = 'exclusiveMaximum';

    /** @var Schema */
    private $rootSchema;

    private $rootData;

    /** @var Ref[] */
    private $refs = array();


    public function readSchema($schemaData)
    {
        return $this->readSchemaDeeper($schemaData);
    }


    protected function readSchemaDeeper($schemaArray, Schema $parentSchema = null)
    {
        $schema = new Schema();
        if (null === $this->rootSchema) {
            $this->rootSchema = $schema;
            $this->rootData = $schemaArray;
        }

        if ($schemaArray instanceof \stdClass) {
            $schemaArray = (array)$schemaArray;
        }

        if (isset($schemaArray[self::TYPE])) {
            $schema->type = new Type($schemaArray[self::TYPE]);
        }

        if (isset($schemaArray[self::PROPERTIES])) {
            $properties = new Properties();
            $schema->properties = $properties;
            foreach ($schemaArray[self::PROPERTIES] as $name => $data) {
                $properties->__set($name, $this->readSchemaDeeper($data, $schema));
            }
        }

        if (isset($schemaArray[self::ADDITIONAL_PROPERTIES])) {
            $additionalProperties = $schemaArray[self::ADDITIONAL_PROPERTIES];
            if ($additionalProperties instanceof \stdClass) {
                $schema->additionalProperties = $this->readSchemaDeeper($additionalProperties, $schema);
            } else {
                $schema->additionalProperties = $additionalProperties;
            }
        }


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
                $schema->additionalItems = $this->readSchemaDeeper($additionalItems, $schema);
            } else {
                $schema->additionalItems = $additionalItems;
            }
        }

        if (isset($schemaArray[self::UNIQUE_ITEMS]) && $schemaArray[self::UNIQUE_ITEMS] === true) {
            $schema->uniqueItems = true;
        }


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
            if ($referencePath === 'http://json-schema.org/draft-04/schema#') {
                $ref = new Ref(
                    $referencePath,
                    SchemaLoader::create()->readSchema(json_decode(file_get_contents(__DIR__ . '/../spec/json-schema.json')))
                );
            }

            elseif ($referencePath === '#') {
                $ref = new Ref($referencePath, $this->rootSchema);
            }

            elseif ($referencePath[0] === '#') {
                $path = explode('/', trim($referencePath, '#/'));
                $branch = &$this->rootData;
                while ($path) {
                    $folder = array_shift($path);
                    if (isset($branch->$folder)) {
                        $branch = &$branch->$folder;
                    } else {
                        throw new \Exception('Could not resolve ' . $referencePath . ', ' . $folder);
                    }
                }
                $ref = new Ref($referencePath, $this->readSchema($branch));
            } else {
                throw new \Exception('Could not resolve ' . $referencePath);
            }
        }

        return $this->refs[$referencePath];
    }


    public function writeSchema()
    {

    }

}

/**
 * @property $minimum
 */
class __stubJsonSchema {}