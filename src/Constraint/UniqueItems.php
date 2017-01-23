<?php

namespace Yaoi\Schema\Constraint;


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
            }
            if (is_bool($value)) {
                $value = '_______BOOL' . $value;
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