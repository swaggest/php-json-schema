<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Schema;

class Type implements Constraint
{
    // TODO deprecate in favour of JsonSchema::<TYPE> ?
    const OBJECT = 'object';
    const STRING = 'string';
    const INTEGER = 'integer';
    const NUMBER = 'number';
    const ARR = 'array';
    const BOOLEAN = 'boolean';
    const NULL = 'null';

    public static function readString($types, &$data)
    {
        if (!is_array($types)) {
            $types = array($types);
        }
        $ok = false;
        foreach ($types as $type) {
            switch ($type) {
                case self::OBJECT:
                    break;
                case self::ARR:
                    break;
                case self::STRING:
                    $ok = true;
                    break;
                case self::NUMBER:
                    $newData = filter_var($data, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
                    $ok = is_float($newData);
                    break;
                case self::INTEGER:
                    $newData = filter_var($data, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                    $ok = is_int($newData);
                    break;
                case self::BOOLEAN:
                    $newData = filter_var($data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    $ok = is_bool($newData);
                    break;
                case self::NULL:
                    break;
            }
            if ($ok) {
                if (isset($newData)) {
                    $data = $newData;
                }
                return true;
            }
        }
        return false;
    }

    public static function isValid($types, $data, $version)
    {
        if (!is_array($types)) {
            $types = array($types);
        }
        $ok = false;
        foreach ($types as $type) {
            switch ($type) {
                case self::OBJECT:
                    $ok = is_object($data);
                    break;
                case self::ARR:
                    $ok = is_array($data);
                    break;
                case self::STRING:
                    $ok = is_string($data);
                    break;
                case self::INTEGER:
                    $ok = is_int($data)
                        || (is_float($data)
                            && ((ceil($data) === $data && $version !== Schema::VERSION_DRAFT_04) // float without fraction is int
                                || abs($data) > PHP_INT_MAX)); // big float accepted for int
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