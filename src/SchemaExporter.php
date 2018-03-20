<?php

namespace Swaggest\JsonSchema;


interface SchemaExporter
{
    /**
     * @return Schema
     */
    public function exportSchema();
}