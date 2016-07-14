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
     * On import raw json data is available to validate against all of constraints.
     * Constraint stores result in referenced variable (raw data value by default).
     * Some constraints may alter result, e.g. `Properties` would create object and fill properties values.
     *
     * @param $data
     * @param $entity
     * @return string invalidation reason, false if valid
     */
    public function importFailed($data, &$entity);

    /**
     *
     * @param $data
     * @param $entity
     * @return mixed
     */
    public function exportFailed($data, &$entity);


    /**
     * Constraints have priority, `Properties` first, `AdditionalProperties` after, etc...
     */
    public static function getPriority();

}