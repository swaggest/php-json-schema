<?php

namespace Yaoi\Schema;

interface Flavour extends Constraint
{
    public function __construct($value);
}