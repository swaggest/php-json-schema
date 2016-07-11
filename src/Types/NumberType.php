<?php

namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\NumberFlavour\Minimum;

class NumberType extends AbstractType
{
    const TYPE = 'number';

    public function import($data)
    {
        $this->validate($data);
        return $data;
    }

    public function export($data)
    {
        $this->validate($data);
        return $data;
    }


    public function validate($data)
    {
        if (!(is_int($data) || is_float($data))) {
            throw new Exception('Invalid number', Exception::INVALID_VALUE);
        }

        $this->validateFlavours($data);
    }

    protected function validateFlavours($data)
    {
        if ($minimum = Minimum::getFromSchema($this->ownerSchema)) {
            if ($minimum->value > $data) {
                throw new Exception('Value < ' . $minimum->value, Exception::INVALID_VALUE);
            }
        }

    }
}