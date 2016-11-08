<?php

namespace Yaoi\Schema;


use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Structure\ObjectItem;

class Schema
{
    /** @var Type */
    public $type;

    /** @var Properties */
    public $properties;


    public function import($data)
    {
        $result = $data;
        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                throw new Exception('Invalid type');
            }
        }

        if ($this->properties !== null) {
            $result = new ObjectItem();
            $this->properties->import($data, $result);
        }


        return $result;
    }

}