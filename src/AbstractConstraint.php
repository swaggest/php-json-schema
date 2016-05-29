<?php

namespace Yaoi\Schema;


abstract class AbstractConstraint extends Base
{
    /**
     * @param Schema $schema
     * @return null|static
     */
    public static function getFromSchema(Schema $schema) {
        $class = self::className();
        if (isset($schema->constraints[$class])) {
            return $schema->constraints[$class];
        }
        return null;
    }
}