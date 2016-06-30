<?php

namespace Yaoi\Schema;


class AbstractFlavour extends AbstractConstraint implements Flavour
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

}