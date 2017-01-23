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

    /** @var Schema */
    private $rootSchema;

    private $rootData;

    /** @var Ref[] */
    private $refs = array();


    public function readSchema($schemaData)
    {
        return $this->readSchemaDeeper($schemaData);
    }


    protected function readSchemaDeeper($schemaData, Schema $parentSchema = null)
    {
        $schema = new Schema();
        if (null === $this->rootSchema) {
            $this->rootSchema = $schema;
            $this->rootData = $schemaData;
        }

        if (isset($schemaData[self::TYPE])) {
            $schema->type = new Type($schemaData[self::TYPE]);
        }

        if (isset($schemaData[self::PROPERTIES])) {
            $properties = new Properties();
            $schema->properties = $properties;
            foreach ($schemaData[self::PROPERTIES] as $name => $data) {
                $properties->__set($name, $this->readSchemaDeeper($data, $schema));
            }
        }

        if (isset($schemaData[self::ADDITIONAL_PROPERTIES])) {
            $schema->additionalProperties = $this->readSchemaDeeper($schemaData[self::ADDITIONAL_PROPERTIES], $schema);
        }

        // should resolve references on load
        if (isset($schemaData[self::REF])) {
            $schema->ref = $this->resolveReference($schemaData[self::REF]);
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
            if ($referencePath === '#') {
                $ref = new Ref($referencePath, $this->rootSchema);
            }

            elseif ($referencePath[0] === '#') {
                $path = explode('/', trim($referencePath, '#/'));
                $branch = &$this->rootData;
                while ($path) {
                    $folder = array_shift($path);
                    if (isset($branch[$folder])) {
                        $branch = &$branch[$folder];
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