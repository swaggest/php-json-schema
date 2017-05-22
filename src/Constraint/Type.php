<?php

namespace Swaggest\JsonSchema\Constraint;

class Type implements Constraint
{
    const OBJECT = 'object';
    const STRING = 'string';
    const INTEGER = 'integer';
    const NUMBER = 'number';
    const ARR = 'array';
    const BOOLEAN = 'boolean';
    const NULL = 'null';

    public static function isValid($types, $data)
    {
        if (!is_array($types)) {
            $types = array($types);
        }
        $ok = false;
        foreach ($types as $type) {
            switch ($type) {
                case self::OBJECT:
                    $ok = $data instanceof \stdClass;
                    break;
                case self::ARR:
                    $ok = is_array($data);
                    break;
                case self::STRING:
                    $ok = is_string($data);
                    break;
                case self::INTEGER:
                    $ok = is_int($data);
                    break;
                case self::NUMBER:
                    $ok = is_int($data) || is_float($data);
                    break;
                case self::BOOLEAN:
                    $ok = is_bool($data);
                    break;
                case self::NULL:
                    $ok = null === $data;
                    break;
            }
            if ($ok) {
                return true;
            }
        }
        return false;
    }


}