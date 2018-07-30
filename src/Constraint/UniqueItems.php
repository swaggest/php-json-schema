<?php

namespace Swaggest\JsonSchema\Constraint;

use Swaggest\JsonSchema\Structure\ObjectItemContract;

class UniqueItems
{
    /**
     * @param array $data
     * @return bool
     * @todo optimize a lot
     */
    public static function isValid(array $data)
    {
        $index = array();
        foreach ($data as $value) {
            if (is_array($value) || $value instanceof \stdClass) {
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $value = '_B' . $value;
            } elseif (is_string($value)) {
                $value = '_S' . $value;
            } elseif (is_int($value)) {
                $value = '_I' . $value;
            } elseif (is_float($value)) {
                $value = '_F' . $value;
            } elseif ($value instanceof ObjectItemContract) {
                $value = json_encode($value);
            }
            $tmp = &$index[$value];
            if ($tmp !== null) {
                return false;
            }
            $tmp = true;
        }
        return true;
    }

}