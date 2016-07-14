<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Schema;

class Type extends AbstractConstraint
{
    const OBJECT = 'object';
    const STRING = 'string';
    const INTEGER = 'integer';
    const NUMBER = 'number';
    const _ARRAY = 'array';
    const BOOLEAN = 'boolean';
    const NULL = 'null';


    public static function getSchemaKey()
    {
        return 'type';
    }

    private $types;
    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->ownerSchema = $ownerSchema;
        $this->types = is_array($schemaValue) ? $schemaValue : array($schemaValue);
    }

    public function importFailed($data, &$entity)
    {
        $ok = false;
        foreach ($this->types as $type) {
            switch ($type) {
                case self::OBJECT:
                    $ok = is_object($data) || is_array($data);break;
                case self::_ARRAY:
                    $ok = is_array($data);break;
                case self::STRING:
                    $ok = is_string($data);break;
                case self::INTEGER:
                    $ok = is_int($data);break;
                case self::NUMBER:
                    $ok = is_int($data) || is_float($data);break;
                case self::BOOLEAN:
                    $ok = is_bool($data);break;
                case self::NULL:
                    $ok = null === $data;break;
            }
            if ($ok) {
                return false;
            }
        }
        return 'Wrong type';
    }

    public function exportFailed($data, &$entity)
    {
        return $this->importFailed($data, $entity);
    }

    public static function getPriority()
    {
        return self::P0;
    }


}