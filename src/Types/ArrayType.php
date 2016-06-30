<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\ArrayFlavour\MinItems;
use Yaoi\Schema\Exception;
use Yaoi\Schema\ArrayFlavour\Items;
use Yaoi\Schema\Transformer;

class ArrayType extends AbstractType implements Transformer
{
    const TYPE = 'array';

    protected function validate($data)
    {
        if (!is_array($data)) {
            throw new Exception('Array expected');
        }
        if ($minItems = MinItems::getFromSchema($this->ownerSchema)) {
            if (count($data) < $minItems->minItems) {
                throw new Exception('Not enough items, ' , $minItems->minItems . ' expected');
            }
        }
    }

    public function import($data)
    {
        $this->validate($data);
        $result = array();
        if ($items = Items::getFromSchema($this->ownerSchema)) {
            foreach ($data as $name => $value) {
                try {
                    $result[$name] = $items->itemsSchema->import($value);
                }
                catch (Exception $exception) {
                    $exception->pushStructureTrace('Items:' . $name);
                    throw $exception;
                }
                unset($data[$name]);
            }
        } else { // TODO implement other flavours
            return $data;
        }
        return $result;
    }

    public function export($data)
    {
        $this->validate($data);
        $result = array();
        if ($items = Items::getFromSchema($this->ownerSchema)) {
            foreach ($data as $name => $value) {
                try {
                    $result[$name] = $items->itemsSchema->export($value);
                } catch (Exception $exception) {
                    $exception->pushStructureTrace('Items:' . $name);
                    throw $exception;
                }
                unset($data[$name]);
            }
        }
        return $result;
    }


}