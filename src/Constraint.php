<?php

namespace Yaoi\Schema;

interface Constraint
{
    const P0 = 0;
    const P1 = 1;


    public function __construct($schemaValue, Schema $ownerSchema = null);

    public static function getSchemaKey();

    public function setOwnerSchema(Schema $ownerSchema);


    /**
     * On import raw json data ($data) is available to be validated against all of constraints.
     * Constraint stores result in referenced variable (raw data value by default).
     * Some constraints may alter result, e.g. `Properties` would create object and fill properties values.
     *
     * @param $data
     * @param $entity
     * @return string invalidation reason, false if valid
     */
    public function importFailed($data, &$entity);

    /**
     * On export php data ($entity) is available to be validated against all the constraints.
     * Constraint stores result in referenced variable ($data).
     *
     * @param $data
     * @param $entity
     * @return mixed
     */
    public function exportFailed($data, &$entity);
}