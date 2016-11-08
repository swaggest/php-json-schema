<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\MagicMap;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Structure\ObjectItem;

class Properties extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $_arrayOfData = array();

    public function __set($name, $column)
    {
        if ($column instanceof Constraint) {
            $schema = new Schema();
            $schema->{$column->getConstraintName()} = $column;
            $column = $schema;
        }

        return parent::__set($name, $column);
    }

    public static function create()
    {
        return new static;
    }

    public function getConstraintName()
    {
        return 'properties';
    }

    public function import($data, ObjectItem $result)
    {
        foreach ($data as $key => $value) {
            if (isset($this->_arrayOfData[$key])) {
                $result->$key = $this->_arrayOfData[$key]->import($value);
            }
        }
    }
}