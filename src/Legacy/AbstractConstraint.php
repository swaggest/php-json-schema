<?php

namespace Yaoi\Schema;


abstract class AbstractConstraint extends Base implements Constraint
{
    /** @var OldSchema */
    protected $ownerSchema;

    public function setOwnerSchema(OldSchema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        return $this;
    }

    /**
     * @param OldSchema $schema
     * @return null|static
     */
    public static function getFromSchema(OldSchema $schema)
    {
        $class = static::className();
        if (isset($schema->constraints[$class])) {
            return $schema->constraints[$class];
        }
        return null;
    }
}