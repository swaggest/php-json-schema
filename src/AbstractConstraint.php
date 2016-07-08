<?php

namespace Yaoi\Schema;


abstract class AbstractConstraint extends Base implements Constraint
{
    /** @var Schema */
    protected $ownerSchema;

    public function setOwnerSchema(Schema $ownerSchema)
    {
        $this->ownerSchema = $ownerSchema;
        return $this;
    }

    /**
     * @param Schema $schema
     * @return null|static
     */
    public static function getFromSchema(Schema $schema)
    {
        $class = static::className();
        if (isset($schema->constraints[$class])) {
            return $schema->constraints[$class];
        }
        return null;
    }
}