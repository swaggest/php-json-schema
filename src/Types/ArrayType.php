<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\ArrayFlavour\Items;
use Yaoi\Schema\Transformer;

class ArrayType extends AbstractType implements Transformer
{
    const TYPE = 'array';

    public function import($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected');
        }
        $result = array();
        if ($items = Items::getFromSchema($this->ownerSchema)) {
            foreach ($data as $name => $value) {
                $result[$name] = $items->itemsSchema->import($value);
                unset($data[$name]);
            }
        }
        return $result;
    }

    public function export($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected');
        }
        $result = array();
        if ($items = Items::getFromSchema($this->ownerSchema)) {
            foreach ($data as $name => $value) {
                $result[$name] = $items->itemsSchema->export($value);
                unset($data[$name]);
            }
        }
        return $result;
    }


}