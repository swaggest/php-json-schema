<?php

namespace Yaoi\Schema;

interface Constraint
{
    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null);
}