<?php

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Meta\MetaHolder;
use Swaggest\JsonSchema\Structure\Nested;

class Wrapper implements SchemaContract, MetaHolder, SchemaExporter, \JsonSerializable
{
    /** @var Schema */
    private $schema;

    /**
     * Keeps reference to original instance for centralized Meta storage
     * @var Schema
     */
    private $originalSchema;

    public $objectItemClass;

    private $cloned = false;

    /**
     * ImmutableSchema constructor.
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
        $this->originalSchema = $schema;
        $this->objectItemClass = $schema->objectItemClass;
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
        return $this->schema->process($data, $options, $path, $result);
    }

    /**
     * @param mixed $data
     * @param Context|null $options
     * @return array|mixed|null|object|\stdClass
     * @throws Exception
     * @throws InvalidValue
     */
    public function in($data, Context $options = null)
    {
        return $this->schema->in($data, $options);
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
        return $this->schema->out($data, $options);
    }

    /**
     * @return string[]
     */
    public function getPropertyNames()
    {
        return $this->schema->getPropertyNames();
    }

    /**
     * @return string[]
     */
    public function getNestedPropertyNames()
    {
        return $this->schema->getNestedPropertyNames();
    }

    public function nested()
    {
        return new Nested($this);
    }

    /**
     * @return null|Constraint\Properties
     */
    public function getProperties()
    {
        return $this->schema->properties;
    }

    /**
     * @param string $name
     * @return null|Schema|SchemaContract
     */
    public function getProperty($name)
    {
        if ($name === Schema::CONST_PROPERTY || $name === Schema::DEFAULT_PROPERTY) {
            return null;
        }
        return isset($this->schema->properties[$name]) ? $this->schema->properties[$name] : null;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'set') {
            if (!$this->cloned) {
                $this->schema = clone $this->schema;
                $this->cloned = true;
            }
            $this->schema->$name($arguments[0]); // todo performance check direct set
            return $this;
        } else {
            throw new Exception('Unknown method:' . $name);
        }
    }

    public function getDefault()
    {
        return $this->schema->default;
    }

    /**
     * @param mixed $default
     * @return $this
     */
    public function setDefault($default)
    {
        if (!$this->cloned) {
            $this->schema = clone $this->schema;
            $this->cloned = true;
        }

        $this->schema->default = $default;
        return $this;
    }

    /**
     * @param string $name
     * @throws Exception
     */
    public function __get($name)
    {
        throw new Exception('Unexpected get: ' . $name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Wrapper
     */
    public function __set($name, $value)
    {
        if (!$this->cloned) {
            $this->schema = clone $this->schema;
            $this->cloned = true;
        }
        $this->schema->$name = $value;
        return $this;
    }

    /**
     * @param string $name
     * @throws Exception
     */
    public function __isset($name)
    {
        throw new Exception('Unexpected isset: ' . $name);
    }

    public function addMeta($meta, $name = null)
    {
        $this->originalSchema->addMeta($meta, $name);
        return $this;
    }

    public function getMeta($name)
    {
        return $this->originalSchema->getMeta($name);
    }

    /**
     * @param Context|null $options
     * @return Structure\ObjectItemContract
     */
    public function makeObjectItem(Context $options = null)
    {
        return $this->schema->makeObjectItem($options);
    }

    public function getObjectItemClass()
    {
        return $this->objectItemClass;
    }

    public function exportSchema()
    {
        return clone $this->schema;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->schema->jsonSerialize();
    }

}
