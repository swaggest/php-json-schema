<?php

namespace Swaggest\JsonSchema;


class Base
{
    public static function className()
    {
        return get_called_class();
    }


    /**
     * @return static
     */
    static function create()
    {
        $args = func_get_args();
        switch (count($args)) {
            case 0:
                return new static();
            case 1:
                return new static($args[0]);
            case 2:
                return new static($args[0], $args[1]);
            case 3:
                return new static($args[0], $args[1], $args[2]);
            case 4:
                return new static($args[0], $args[1], $args[2], $args[3]);
            case 5:
                return new static($args[0], $args[1], $args[2], $args[3], $args[4]);
            case 6:
                return new static($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
        }
        return new static;
    }


}