<?php


namespace Swaggest\JsonSchema\Structure;


interface WithResolvedValue
{
    public function setResolvedValue($value);

    public function getResolvedValue();

    public function hasResolvedValue();

}