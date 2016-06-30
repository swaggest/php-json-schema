<?php

namespace Yaoi\Schema\ArrayFlavour;


use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Flavour;
use Yaoi\Schema\Validator;

class MinItems extends AbstractConstraint implements Flavour
{
    const KEY = 'minItems';

    /** @var int */
    public $minItems;
    public function __construct($minItems)
    {
        $this->minItems = $minItems;
    }


    public function isValid($data)
    {
        return count($data) >= $this->minItems;
    }

}