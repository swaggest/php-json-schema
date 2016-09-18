<?php
namespace Yaoi\Schema\Types;

use Yaoi\Schema\Exception;
use Yaoi\Schema\StringFlavour\MaxLength;
use Yaoi\Schema\StringFlavour\MinLength;
use Yaoi\Schema\StringFlavour\Pattern;

class StringType extends AbstractType
{
    const TYPE = 'string';

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

    protected function validate($data)
    {
        if (!is_string($data)) {
            throw new Exception("String required", Exception::INVALID_VALUE);
        }

        if ($minLength = MinLength::getFromSchema($this->ownerSchema)) {
            if ($minLength->value > strlen($data)) {
                throw new Exception("Minimal length is " . $minLength->value, Exception::INVALID_VALUE);
            }
        }
        if ($maxLength = MaxLength::getFromSchema($this->ownerSchema)) {
            if ($maxLength->value < strlen($data)) {
                throw new Exception("Maximal length is " . $minLength->value, Exception::INVALID_VALUE);
            }
        }
        if ($pattern = Pattern::getFromSchema($this->ownerSchema)) {
            if (preg_match('~' . str_replace('~', '\\~', $pattern->value) . '~', $data)) {
                throw new Exception("Pattern check failed", Exception::INVALID_VALUE);
            }
        }
    }
}