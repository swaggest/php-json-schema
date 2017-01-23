<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\NG\MagicMap;
use Yaoi\Schema\NG\Schema;
use Yaoi\Schema\NG\SchemaLoader;
use Yaoi\Schema\Structure\ObjectItem;

/**
 * @method Schema __get($key)
 */
class Properties extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $_arrayOfData = array();

    public function __set($name, $column)
    {
        if ($column instanceof Constraint) {
            $schema = new Schema();
            $column->setToSchema($schema);
            return parent::__set($name, $schema);
        }

        return parent::__set($name, $column);
    }

    public function setToSchema(Schema $schema)
    {
        $schema->properties = $this;
    }

    public static function create()
    {
        return new static;
    }

    public function import($data, ObjectItem $result, Schema $schema = null)
    {
        $traceHelper = Schema::$traceHelper;

        $additionalProperties = $schema->additionalProperties;

        foreach ($data as $key => $value) {
            if (isset($this->_arrayOfData[$key])) {
                $traceHelper->push()->addData(SchemaLoader::PROPERTIES . ':' . $key);
                $result->$key = $this->_arrayOfData[$key]->import($value);
                $traceHelper->pop();
            } else {
                if (null !== $additionalProperties) {
                    $traceHelper->push()->addData(SchemaLoader::ADDITIONAL_PROPERTIES);
                    $value = $additionalProperties->import($value);
                    $traceHelper->pop();
                }
                $result->$key = $value;
            }
        }
    }

    public function export(ObjectItem $data)
    {
        $result = array();
        foreach ($this->_arrayOfData as $key => $value) {
            $result[$key] = $value->export($data->$key);
        }
        return $result;
    }

    /** @var Schema */
    private $additionalProperties;

    /**
     * @param Schema $additionalProperties
     * @return Properties
     */
    public function setAdditionalProperties(Schema $additionalProperties = null)
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }
}